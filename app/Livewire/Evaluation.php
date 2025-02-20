<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Schedule;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
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
