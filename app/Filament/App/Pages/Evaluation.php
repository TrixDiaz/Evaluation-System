<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;

class Evaluation extends Page
{

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.evaluation';

    protected static ?string $navigationGroup = 'Evaluation';

    protected static ?string $navigationLabel = 'Evaluation Result';
}
