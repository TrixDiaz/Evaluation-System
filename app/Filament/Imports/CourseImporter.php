<?php

namespace App\Filament\Imports;

use App\Models\Course;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class CourseImporter extends Importer
{
    protected static ?string $model = Course::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name'),
            ImportColumn::make('code'),
        ];
    }

    public function resolveRecord(): ?Course
    {
        // Try to find existing record or create new one
        $course = Course::firstOrNew(
            ['code' => $this->data['code']], // Search by unique code
            [
                'name' => $this->data['name'],
                'code' => $this->data['code'],
            ]
        );

        // Update the record if it exists
        if ($course->exists) {
            $course->name = $this->data['name'];
        }

        return new $course;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your course import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
