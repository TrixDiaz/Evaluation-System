<?php

namespace App\Filament\Imports;

use App\Models\Subject;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class SubjectImporter extends Importer
{
    protected static ?string $model = Subject::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name'),
        ];
    }

    public function resolveRecord(): ?Subject
    {
        // Try to find existing record or create new one
        $subject = Subject::firstOrNew(
            [
                'name' => $this->data['name'],
            ]
        );

        // Update the record if it exists
        if ($subject->exists) {
            $subject->name = $this->data['name'];
        }

        return new $subject;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your subject import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
