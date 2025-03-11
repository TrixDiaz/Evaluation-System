<?php

namespace App\Filament\Exports;

use App\Models\Schedule;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ScheduleExporter extends Exporter
{
    protected static ?string $model = Schedule::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('title')
                ->label('Title'),

            ExportColumn::make('course.name')
                ->label('Course'),

            ExportColumn::make('instructor.name')
                ->label('Instructor'),

            ExportColumn::make('room.name')
                ->label('Room'),

            ExportColumn::make('start_time')
                ->label('Start Time')
                ->formatStateUsing(fn($state) => $state ? $state->format('Y-m-d H:i:s') : null),

            ExportColumn::make('end_time')
                ->label('End Time')
                ->formatStateUsing(fn($state) => $state ? $state->format('Y-m-d H:i:s') : null),

            ExportColumn::make('day_of_week')
                ->label('Day of Week'),

            ExportColumn::make('status')
                ->label('Status'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your schedule export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
