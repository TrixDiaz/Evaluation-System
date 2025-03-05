<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\StudentEvaluationResource\Pages;
use App\Filament\App\Resources\StudentEvaluationResource\RelationManagers;
use App\Models\StudentEvaluation;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentEvaluationResource extends Resource implements HasShieldPermissions
{

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }
    protected static ?string $model = StudentEvaluation::class;

    protected static ?string $navigationGroup = 'Evaluation';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?String $label = 'Create Student Evaluations';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('schedule_id')
                            ->label('Schedule')
                            ->relationship('schedule', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required(),
                    ]),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(65535),
                    ]),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Repeater::make('questions')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('question')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('type')
                                    ->options([
                                        'text' => 'Text Answer',
                                        'multiple_choice' => 'Multiple Choice',
                                        'rating' => 'Rating',
                                    ])
                                    ->required()
                                    ->reactive(),
                                Forms\Components\Repeater::make('options')
                                    ->schema([
                                        Forms\Components\TextInput::make('option')
                                            ->required(),
                                    ])
                                    ->visible(fn($get) => $get('type') === 'multiple_choice')
                                    ->columns(1),
                            ]),
                    ])->columnSpanFull()
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudentEvaluations::route('/'),
            'create' => Pages\CreateStudentEvaluation::route('/create'),
            'edit' => Pages\EditStudentEvaluation::route('/{record}/edit'),
        ];
    }
}
