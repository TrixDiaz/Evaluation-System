<?php

namespace App\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class Dashboard extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Evaluation Form')
            ->query(\App\Models\User::query())
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name')
                    ->label('Subject'),
                \Filament\Tables\Columns\TextColumn::make('email')
                    ->label('Schedule'),
                \Filament\Tables\Columns\TextColumn::make('room')
                    ->label('Room')
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('Course')
                    ->options(\App\Models\Course::where('is_active', true)->pluck('name','id'))
                    ->native(false)
                    ->columnSpanFull(),
                \Filament\Tables\Filters\SelectFilter::make('Professor')
                    ->options(\App\Models\User::all()->pluck('name','id'))
                    ->native(false),
                \Filament\Tables\Filters\SelectFilter::make('Semester')
                    ->options([
                        '1st' => '1st Semester',
                        '2nd' => '2nd Semester',
                    ])
                    ->native(false),
                \Filament\Tables\Filters\SelectFilter::make('Year')
                    ->options([
                        '2015-2016' => '2015 - 2016',
                        '2016-2017' => '2016 - 2017',
                        '2017-2018' => '2017 - 2018',
                    ])
                    ->native(false),
            ], layout: \Filament\Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                \Filament\Tables\Actions\Action::make('first')
                    ->label('Copus 1')
                    ->button(),
                \Filament\Tables\Actions\Action::make('first')
                    ->label('Copus 2')
                    ->button(),
                \Filament\Tables\Actions\Action::make('first')
                    ->label('Copus 3')
                    ->button(),
            ])
            ->filtersFormColumns(3)
            ->emptyStateHeading('No Tickets yet')
            ->defaultPaginationPageOption(10)
            ->paginationPageOptions([5, 10, 25, 50])
            ->striped();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
