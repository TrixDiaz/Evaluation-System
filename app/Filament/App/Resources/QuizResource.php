<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\QuizResource\Pages;
use App\Filament\App\Resources\QuizResource\RelationManagers;
use App\Models\Quiz;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;

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

                Forms\Components\TextInput::make('time_limit')
                    ->numeric()
                    ->label('Time Limit (minutes)')
                    ->nullable(),

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
                Tables\Columns\TextColumn::make('time_limit')
                    ->numeric()
                    ->sortable(),
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
            RelationManagers\QuestionRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizzes::route('/'),
            'create' => Pages\CreateQuiz::route('/create'),
            'edit' => Pages\EditQuiz::route('/{record}/edit'),
        ];
    }
}
