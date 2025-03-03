<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Schedule;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class Evaluation extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        $filters = $this->getTableFilterState('table');

        return $table
            ->heading('Evaluation Form')
            ->query(
                Schedule::query()
                    ->when(
                        $filters['course_id'] ?? null,
                        fn(Builder $query, $courseId): Builder => $query->whereHas(
                            'course',
                            fn($q) => $q->where('id', $courseId)
                        )
                    )
                    ->when(
                        $filters['professor_id'] ?? null,
                        fn(Builder $query, $professorId): Builder => $query->whereHas(
                            'professor',
                            fn($q) => $q->where('id', $professorId)
                        )
                    )
                    ->when(
                        $filters['semester'] ?? null,
                        fn(Builder $query, $semester): Builder => $query->where('semester', $semester)
                    )
                    ->when(
                        $filters['year'] ?? null,
                        fn(Builder $query, $year): Builder => $query->where('year', $year)
                    )
            )
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('courseprof')
                    ->label('Course and Professor')
                    ->default(fn($record): string => $record->course?->name)
                    ->description(fn($record): string => $record->professor?->name),
                \Filament\Tables\Columns\TextColumn::make('roomsubject')
                    ->label('Room & Subject')
                    ->default(fn($record): string => $record->room?->name)
                    ->description(fn($record): string => $record->subject?->name),
                \Filament\Tables\Columns\TextColumn::make('yearsem')
                    ->label('Year & Semester')
                    ->default(fn($record): string => $record->semester)
                    ->description(fn($record): string => $record->year),
                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->date('Y-m-d'),
                \Filament\Tables\Columns\TextColumn::make('updated_at')
                    ->since(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'name')
                    ->preload()
                    ->native(false)
                    ->columnSpanFull(),

                \Filament\Tables\Filters\SelectFilter::make('professor_id')
                    ->label('Professor')
                    ->options(function () use ($filters) {
                        $authUser = \Illuminate\Support\Facades\Auth::user();

                        // Get only the professors assigned to the dean
                        $professorIds = \App\Models\DeanProfessorList::where('parent_id', $authUser->id)
                            ->pluck('child_id')
                            ->toArray();

                        $query = User::role('professor')->whereIn('id', $professorIds);



                        if (isset($filters['course_id'])) {
                            $query->whereHas('schedules', function ($q) use ($filters) {
                                $q->where('course_id', $filters['course_id']);
                            });
                        }

                        return $query->pluck('name', 'id')->toArray();
                    })
                    ->native(false),

                \Filament\Tables\Filters\SelectFilter::make('semester')
                    ->options([
                        '1st Semester' => '1st Semester',
                        '2nd Semester' => '2nd Semester',
                        'Summer' => 'Summer'
                    ])
                    ->optionsLimit(3)
                    ->searchable(false)
                    ->native(false),

                \Filament\Tables\Filters\SelectFilter::make('year')
                    ->options(function () use ($filters) {
                        $query = Schedule::query();

                        if (isset($filters['course_id'])) {
                            $query->where('course_id', $filters['course_id']);
                        }

                        if (isset($filters['professor_id'])) {
                            $query->where('professor_id', $filters['professor_id']);
                        }

                        if (isset($filters['semester'])) {
                            $query->where('semester', $filters['semester']);
                        }

                        return $query->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year', 'year')
                            ->toArray();
                    })
                    ->native(false),
            ], layout: \Filament\Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                \Filament\Tables\Actions\Action::make('copus1')
                    ->label('COPUS 1')
                    ->button()
                    ->color(fn($record) => \App\Models\Evaluation::where('schedule_id', $record->id)
                        ->where('dean_id', auth()->user()->id)
                        ->where('evaluation_type', 'copus1')
                        ->exists() ? 'gray' : 'primary')
                    ->disabled(function ($record) {
                        return \App\Models\Evaluation::where('schedule_id', $record->id)
                            ->where('dean_id', auth()->user()->id)
                            ->where('evaluation_type', 'copus1')
                            ->exists();
                    })
                    ->tooltip(function ($record) {
                        $evaluated = \App\Models\Evaluation::where('schedule_id', $record->id)
                            ->where('dean_id', auth()->user()->id)
                            ->where('evaluation_type', 'copus1')
                            ->exists();

                        return $evaluated ? 'COPUS 1 has already been evaluated' : 'Create COPUS 1 evaluation';
                    })
                    ->form(fn($record) => \App\Services\Evaluation::schema($record))
                    ->modalWidth('7xl')
                    ->action(function (array $data, $record) {
                        $success = \App\Models\Evaluation::create([
                            'dean_id' => auth()->user()->id,
                            'schedule_id' => $record->id,
                            'evaluation_type' => 'copus1',
                            'observation_date' => now(),
                            'student_activities' => $data['student_activities'] ?? [],
                            'instructor_activities' => $data['instructor_activities'] ?? [],
                            'additional_comments' => $data['additional_comments'],
                        ]);

                        Notification::make()
                            ->title('New COPUS 1 Evaluation Submitted')
                            ->icon('heroicon-o-document-text')
                            ->body("A new COPUS 1 evaluation has been submitted for your {$record->course->name} course.")
                            ->sendToDatabase($record->professor);

                        Notification::make()
                            ->success()
                            ->title('COPUS 1 Evaluation Submitted')
                            ->body("The evaluation has been submitted successfully and {$record->professor->name} has been notified.")
                            ->persistent()
                            ->send();
                    }),

                \Filament\Tables\Actions\Action::make('copus2')
                    ->label('COPUS 2')
                    ->button()
                    ->color(fn($record) => \App\Models\Evaluation::where('schedule_id', $record->id)
                        ->where('dean_id', auth()->user()->id)
                        ->where('evaluation_type', 'copus2')
                        ->exists() ? 'gray' : 'warning')
                    ->disabled(function ($record) {
                        return \App\Models\Evaluation::where('schedule_id', $record->id)
                            ->where('dean_id', auth()->user()->id)
                            ->where('evaluation_type', 'copus2')
                            ->exists();
                    })
                    ->tooltip(function ($record) {
                        $evaluated = \App\Models\Evaluation::where('schedule_id', $record->id)
                            ->where('dean_id', auth()->user()->id)
                            ->where('evaluation_type', 'copus2')
                            ->exists();

                        return $evaluated ? 'COPUS 2 has already been evaluated' : 'Create COPUS 2 evaluation';
                    })
                    ->form(fn($record) => \App\Services\Evaluation::schema($record))
                    ->modalWidth('7xl')
                    ->action(function (array $data, $record) {
                        $success = \App\Models\Evaluation::create([
                            'dean_id' => auth()->user()->id,
                            'schedule_id' => $record->id,
                            'evaluation_type' => 'copus2',
                            'observation_date' => now(),
                            'student_activities' => $data['student_activities'] ?? [],
                            'instructor_activities' => $data['instructor_activities'] ?? [],
                            'additional_comments' => $data['additional_comments'],
                        ]);

                        Notification::make()
                            ->title('New COPUS 2 Evaluation Submitted')
                            ->icon('heroicon-o-document-text')
                            ->body("A new COPUS 2 evaluation has been submitted for your {$record->course->name} course.")
                            ->sendToDatabase($record->professor);

                        Notification::make()
                            ->success()
                            ->title('COPUS 2 Evaluation Submitted')
                            ->body("The evaluation has been submitted successfully and {$record->professor->name} has been notified.")
                            ->persistent()
                            ->send();
                    }),

                \Filament\Tables\Actions\Action::make('copus3')
                    ->label('COPUS 3')
                    ->button()
                    ->color(fn($record) => \App\Models\Evaluation::where('schedule_id', $record->id)
                        ->where('dean_id', auth()->user()->id)
                        ->where('evaluation_type', 'copus3')
                        ->exists() ? 'gray' : 'success')
                    ->disabled(function ($record) {
                        return \App\Models\Evaluation::where('schedule_id', $record->id)
                            ->where('dean_id', auth()->user()->id)
                            ->where('evaluation_type', 'copus3')
                            ->exists();
                    })
                    ->tooltip(function ($record) {
                        $evaluated = \App\Models\Evaluation::where('schedule_id', $record->id)
                            ->where('dean_id', auth()->user()->id)
                            ->where('evaluation_type', 'copus3')
                            ->exists();

                        return $evaluated ? 'COPUS 3 has already been evaluated' : 'Create COPUS 3 evaluation';
                    })
                    ->form(fn($record) => \App\Services\Evaluation::schema($record))
                    ->modalWidth('7xl')
                    ->action(function (array $data, $record) {
                        $success = \App\Models\Evaluation::create([
                            'dean_id' => auth()->user()->id,
                            'schedule_id' => $record->id,
                            'evaluation_type' => 'copus3',
                            'observation_date' => now(),
                            'student_activities' => $data['student_activities'] ?? [],
                            'instructor_activities' => $data['instructor_activities'] ?? [],
                            'additional_comments' => $data['additional_comments'],
                        ]);

                        Notification::make()
                            ->title('New COPUS 3 Evaluation Submitted')
                            ->icon('heroicon-o-document-text')
                            ->body("A new COPUS 3 evaluation has been submitted for your {$record->course->name} course.")
                            ->sendToDatabase($record->professor);

                        Notification::make()
                            ->success()
                            ->title('COPUS 3 Evaluation Submitted')
                            ->body("The evaluation has been submitted successfully and {$record->professor->name} has been notified.")
                            ->persistent()
                            ->send();
                    }),
            ])
            ->filtersFormColumns(3)
            ->emptyStateHeading('No Schedules found')
            ->defaultPaginationPageOption(10)
            ->paginationPageOptions([5, 10, 25, 50])
            ->striped();
    }
    public function render()
    {
        return view('livewire.evaluation');
    }
}
