<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\EvaluationResource\Pages;
use App\Filament\App\Resources\EvaluationResource\RelationManagers;
use App\Models\Quiz;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EvaluationResource extends Resource
{
    protected static ?string $model = Quiz::class;

protected static ?string $modelLabel = 'Evaluation';

    protected static ?string $navigationLabel = 'Evaluation';

    protected static ?string $navigationGroup = 'Evaluation';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('description')
                    ->maxLength(65535),

                Forms\Components\Toggle::make('is_published')
                    ->default(false),

                Forms\Components\Builder::make('header_content')
                    ->blocks([
                        Forms\Components\Builder\Block::make('heading')
                            ->schema([
                                Forms\Components\TextInput::make('text')
                                    ->required(),
                                Forms\Components\Select::make('level')
                                    ->options([
                                        'h1' => 'Heading 1',
                                        'h2' => 'Heading 2',
                                        'h3' => 'Heading 3',
                                    ])
                                    ->required(),
                            ]),
                        Forms\Components\Builder\Block::make('paragraph')
                            ->schema([
                                Forms\Components\RichEditor::make('content')
                                    ->required(),
                            ]),
                    ]),

                Forms\Components\Builder::make('footer_content')
                    ->blocks([
                        Forms\Components\Builder\Block::make('text')
                            ->schema([
                                Forms\Components\RichEditor::make('content')
                                    ->required(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean(),
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
            EvaluationResource\RelationManagers\QuestionRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvaluations::route('/'),
            'create' => Pages\CreateEvaluation::route('/create'),
            'edit' => Pages\EditEvaluation::route('/{record}/edit'),
        ];
    }
}
