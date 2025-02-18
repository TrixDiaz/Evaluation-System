<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-m-home';

    protected static string $view = 'filament.app.pages.dashboard';

    protected static ?string $title = 'Home';
    protected static ?string $navigationLabel = 'Home';
}
