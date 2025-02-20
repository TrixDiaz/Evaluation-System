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
                ->maxLength(255)
                ->live(onBlur: true)
//                ->unique(\App\Models\Building::class, 'name', ignoreRecord: true)
                ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                    /*if ($operation !== 'create') {
                        return;
                    }*/

                    $set('slug', \Illuminate\Support\Str::slug($state));
                }),

            Forms\Components\TextInput::make('slug')
                ->disabled()
                ->dehydrated()
                ->required()
                ->maxLength(255)
                ->unique($model, 'slug', ignoreRecord: true),
        ];
    }
}
