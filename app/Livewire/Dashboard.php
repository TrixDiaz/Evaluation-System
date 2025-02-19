<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\User;
use App\Models\Schedule;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;

class Dashboard extends Component implements HasForms, HasTable
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
                        fn(Builder $query, $courseId): Builder =>
                        $query->whereHas(
                            'course',
                            fn($q) =>
                            $q->where('id', $courseId)
                        )
                    )
                    ->when(
                        $filters['professor_id'] ?? null,
                        fn(Builder $query, $professorId): Builder =>
                        $query->whereHas(
                            'professor',
                            fn($q) =>
                            $q->where('id', $professorId)
                        )
                    )
                    ->when(
                        $filters['semester'] ?? null,
                        fn(Builder $query, $semester): Builder =>
                        $query->where('semester', $semester)
                    )
                    ->when(
                        $filters['year'] ?? null,
                        fn(Builder $query, $year): Builder =>
                        $query->where('year', $year)
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
                        if (!isset($filters['course_id'])) {
                            return User::role('professor')
                                ->pluck('name', 'id')
                                ->toArray();
                        }

                        return User::role('professor')
                            ->whereHas('schedules', function ($query) use ($filters) {
                                $query->where('course_id', $filters['course_id']);
                            })
                            ->pluck('name', 'id')
                            ->toArray();
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
                    ->label('Copus 1')
                    ->button()
                    ->form(\App\Services\CopusSheet::schema())
                    ->modalWidth('full')
                    ->action(function (array $data, Schedule $record) {
                        try {
                            // Debug the raw form data
                            \Log::info('Raw Form Data:', $data);

                            $formData = $data['data'] ?? [];

                            // Get course name from the schedule if not provided
                            $courseName = $formData['course_name'] ?? $record->course?->name ?? '';

                            // Process student activities
                            $studentActivities = [];
                            if (isset($data['data']['student_activities'])) {
                                foreach ($data['data']['student_activities'] as $time => $activities) {
                                    $studentActivities[$time] = [];
                                    foreach ($activities as $code => $value) {
                                        if ($value === "1") {
                                            $studentActivities[$time][] = $code;
                                        }
                                    }
                                }
                            }

                            // Process instructor activities
                            $instructorActivities = [];
                            if (isset($data['data']['instructor_activities'])) {
                                foreach ($data['data']['instructor_activities'] as $time => $activities) {
                                    $instructorActivities[$time] = [];
                                    foreach ($activities as $code => $value) {
                                        if ($value === "1") {
                                            $instructorActivities[$time][] = $code;
                                        }
                                    }
                                }
                            }

                            // Process comments
                            $comments = [];
                            if (isset($data['data']['comments'])) {
                                foreach ($data['data']['comments'] as $time => $comment) {
                                    if (!empty($comment)) {
                                        $comments[$time] = $comment;
                                    }
                                }
                            }

                            // Debug the processed arrays
                            \Log::info('Processed Arrays:', [
                                'course_name' => $courseName,
                                'student_activities' => $studentActivities,
                                'instructor_activities' => $instructorActivities,
                                'comments' => $comments
                            ]);

                            $observation = $record->copusObservations()->updateOrCreate(
                                ['observation_number' => 1],
                                [
                                    'observer_name' => $formData['observer_name'] ?? auth()->user()->name,
                                    'observation_date' => $formData['observation_date'] ?? now(),
                                    'course_name' => $courseName,
                                    'student_activities' => $studentActivities ?: [],
                                    'instructor_activities' => $instructorActivities ?: [],
                                    'comments' => $comments ?: [],
                                    'additional_comments' => $formData['additional_comments'] ?? '',
                                ]
                            );

                            // Debug the saved observation
                            \Log::info('Saved Observation:', $observation->toArray());

                            // Success notification
                            Notification::make()
                                ->success()
                                ->title('COPUS Observation Saved')
                                ->body('The observation data has been successfully saved.')
                                ->send();
                        } catch (\Exception $e) {
                            // Error logging
                            \Log::error('COPUS Save Error:', [
                                'error' => $e->getMessage(),
                                'trace' => $e->getTraceAsString(),
                                'data' => $data
                            ]);

                            // Error notification
                            Notification::make()
                                ->danger()
                                ->title('Error Saving COPUS Observation')
                                ->body('There was a problem saving the observation data. Please try again.')
                                ->send();

                            throw $e;
                        }
                    }),

                \Filament\Tables\Actions\Action::make('copus2')
                    ->label('Copus 2')
                    ->button()
                    ->form(\App\Services\CopusSheet::schema())
                    ->modalWidth('full')
                    ->action(function (array $data, Schedule $record) {
                        if (!isset($data['data'])) {
                            throw new \Exception('Form data structure is invalid');
                        }

                        $record->copusObservations()->updateOrCreate(
                            ['observation_number' => 2],
                            [
                                'observer_name' => $data['data']['observer_name'] ?? '',
                                'observation_date' => $data['data']['observation_date'] ?? now(),
                                'course_name' => $data['data']['course_name'] ?? '',
                                'student_activities' => $data['data']['student_activities'] ?? [],
                                'instructor_activities' => $data['data']['instructor_activities'] ?? [],
                                'comments' => $data['data']['comments'] ?? [],
                                'additional_comments' => $data['data']['additional_comments'] ?? '',
                            ]
                        );
                    }),

                \Filament\Tables\Actions\Action::make('copus3')
                    ->label('Copus 3')
                    ->button()
                    ->form(\App\Services\CopusSheet::schema())
                    ->modalWidth('full')
                    ->action(function (array $data, Schedule $record) {
                        if (!isset($data['data'])) {
                            throw new \Exception('Form data structure is invalid');
                        }

                        $record->copusObservations()->updateOrCreate(
                            ['observation_number' => 3],
                            [
                                'observer_name' => $data['data']['observer_name'] ?? '',
                                'observation_date' => $data['data']['observation_date'] ?? now(),
                                'course_name' => $data['data']['course_name'] ?? '',
                                'student_activities' => $data['data']['student_activities'] ?? [],
                                'instructor_activities' => $data['data']['instructor_activities'] ?? [],
                                'comments' => $data['data']['comments'] ?? [],
                                'additional_comments' => $data['data']['additional_comments'] ?? '',
                            ]
                        );
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
        return view('livewire.dashboard');
    }
}
