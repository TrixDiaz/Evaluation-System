<?php

namespace App\Filament\App\Resources\EvaluationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class QuestionRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('question_type')
                    ->options([
                        'text' => 'Text Answer',
                        'multiple_choice' => 'Multiple Choice',
                        'rating' => 'Rating (1-5)',
                    ])
                    ->required()
                    ->live(),

                Forms\Components\Builder::make('question_content')
                    ->blocks([
                        Forms\Components\Builder\Block::make('text_question')
                            ->schema([
                                Forms\Components\RichEditor::make('question_text')
                                    ->required(),
                                // Forms\Components\TextInput::make('points')
                                //     ->numeric()
                                //     ->default(1)
                                //     ->required(),
                            ]),
                        Forms\Components\Builder\Block::make('image_question')
                            ->schema([
                                Forms\Components\FileUpload::make('image')
                                    ->image()
                                    ->directory('question-images'),
                                Forms\Components\RichEditor::make('question_text')
                                    ->required(),
                                // Forms\Components\TextInput::make('points')
                                //     ->numeric()
                                //     ->default(1)
                                //     ->required(),
                            ]),
                    ]),

                Forms\Components\Builder::make('answer_content')
                    ->visible(fn(Forms\Get $get): bool => $get('question_type') === 'multiple_choice')
                    ->blocks([
                        Forms\Components\Builder\Block::make('multiple_choice')
                            ->schema([
                                Forms\Components\Repeater::make('choices')
                                    ->schema([
                                        Forms\Components\TextInput::make('option_text')
                                            ->required(),
                                        Forms\Components\Toggle::make('is_correct')
                                            ->default(false),
                                    ])
                                    ->columns(2)
                                    ->minItems(2)
                                    ->maxItems(6),
                            ]),
                    ]),

                // Forms\Components\TextInput::make('correct_answer')
                //     ->visible(fn (Forms\Get $get): bool => $get('question_type') === 'text')
                //     ->required(fn (Forms\Get $get): bool => $get('question_type') === 'text'),

                Forms\Components\Radio::make('rating_correct_answer')
                    ->visible(fn(Forms\Get $get): bool => $get('question_type') === 'rating')
                    ->options([
                        1 => '1 - Very Poor',
                        2 => '2 - Poor',
                        3 => '3 - Average',
                        4 => '4 - Good',
                        5 => '5 - Excellent',
                    ])
                    ->required(fn(Forms\Get $get): bool => $get('question_type') === 'rating'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('questions')
            ->columns([
                Tables\Columns\TextColumn::make('question_type'),
                // Tables\Columns\TextColumn::make('points'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
