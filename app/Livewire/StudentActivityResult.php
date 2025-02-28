<?php

namespace App\Livewire;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class
StudentActivityResult extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->heading('Student Activity Result')
            ->query(
                \App\Models\User::query()
            )
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name'),
            ])
            ->emptyStateHeading('No Students Result yet')
            ->defaultPaginationPageOption(5)
            ->paginationPageOptions([5, 10, 25, 50])
            ->striped();
    }
}
