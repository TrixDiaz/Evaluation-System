<?php

namespace App\Services;

use Filament\Forms;

final class DynamicForm
{
    public static function schema($model): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
        ];
    }
}
