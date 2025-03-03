<?php

namespace App\Filament\App\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class StudentEvalResult extends Page implements HasTable
{
    use InteractsWithTable;

    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static string $view = 'filament.app.pages.student-eval-result';

    protected static ?string $navigationGroup = 'Evaluation';

    protected static ?string $title = 'Student Evaluation Result';

    protected static ?int $navigationSort = 4;

    public function table(Table $table): table
    {
        return $table
            ->query(
                \App\Models\StudentEvaluationResponse::query()
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
                    ->default(fn($record): string => $record->year)
                    ->description(fn($record): string => $record->semester),
                \Filament\Tables\Columns\TextColumn::make('evaluated')
                    ->label('Evaluated')
                    ->default(fn($record): string => $record->evaluated)
                    ->description(fn($record): string => $record->evaluated),
                \Filament\Tables\Columns\TextColumn::make('score')
                    ->label('Score')
                    ->default(fn($record): string => $record->score)
                    ->description(fn($record): string => $record->score),
            ]);
    }
}
