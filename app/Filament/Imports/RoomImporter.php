<?php

namespace App\Filament\Imports;

use App\Models\Room;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class RoomImporter extends Importer
{
    protected static ?string $model = Room::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name'),
        ];
    }

    public function resolveRecord(): ?Room
    {
        // Try to find existing record or create new one
        $room = Room::firstOrNew(
            [
                'name' => $this->data['name'],
            ]
        );

        // Update the record if it exists
        if ($room->exists) {
            $room->name = $this->data['name'];
        }

        return new $room;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your room import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
