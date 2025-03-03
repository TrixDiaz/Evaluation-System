<?php

namespace App\Filament\App\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;

class Evaluation extends Page
{
    use HasPageShield;
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static string $view = 'filament.app.pages.evaluation';

    protected static ?string $navigationGroup = 'Evaluation';

    protected static ?string $navigationLabel = 'Copus Sheet Result';

    protected static ?int $navigationSort = 1;
}
