<?php

namespace App\Filament\Imports;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Carbon;

class ScheduleImporter extends Importer
{
    protected static ?string $model = Schedule::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('title')
                ->label('Title')
                ->requiredMapping()
                ->example('Introduction to Programming'),

            ImportColumn::make('course')
                ->label('Course')
                ->relationship()
                ->example('Computer Science 101'),
            ImportColumn::make('instructor')
                ->label('Instructor')
                ->relationship()
                ->example('John Doe'),

            ImportColumn::make('room')
                ->label('Room')
                ->relationship()
                ->example('Room 101'),

            ImportColumn::make('start_time')
                ->label('Start Time')
                ->castStateUsing(fn(string $state): ?Carbon => Carbon::parse($state))
                ->example('2023-09-01 09:00:00'),

            ImportColumn::make('end_time')
                ->label('End Time')
                ->castStateUsing(fn(string $state): ?Carbon => Carbon::parse($state))
                ->example('2023-09-01 10:30:00'),

            ImportColumn::make('day_of_week')
                ->label('Day of Week')
                ->example('Monday'),

            ImportColumn::make('status')
                ->label('Status')
                ->example('active'),
        ];
    }

    public function resolveRecord(): ?Schedule
    {
        // Try to find existing schedule based on unique attributes
        return Schedule::firstOrNew([
            'title' => $this->data['title'],
            'start_time' => $this->data['start_time'] ?? null,
            'day_of_week' => $this->data['day_of_week'] ?? null,
        ]);
    }

    protected function beforeCreate(): void
    {
        // Handle course relationship
        if (isset($this->data['course'])) {
            $course = Course::firstOrCreate(['name' => $this->data['course']]);
            $this->record->course_id = $course->id;
        }

        // Handle instructor relationship
        if (isset($this->data['instructor'])) {
            $instructor = User::firstOrCreate(['name' => $this->data['instructor']]);
            $this->record->instructor_id = $instructor->id;
        }

        // Handle room relationship
        if (isset($this->data['room'])) {
            $room = Room::firstOrCreate(['name' => $this->data['room']]);
            $this->record->room_id = $room->id;
        }

        // Set default status if not provided
        if (!isset($this->data['status'])) {
            $this->data['status'] = 'active';
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your schedule import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
