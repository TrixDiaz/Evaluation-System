<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ScheduleResource\Pages;
use App\Filament\App\Resources\ScheduleResource\RelationManagers;
use App\Models\Schedule;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScheduleResource extends Resource implements HasShieldPermissions
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
            'publish',
            'restore',
            'restore_any',
            'force_delete',
            'force_delete_any',
        ];
    }
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Resource Group';

    protected static ?int $navigationSort = 4;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([

                        Forms\Components\Select::make('course_id')
                            ->relationship(
                                'course',
                                'name',
                                fn(Builder $query) => $query->where('is_active', true)
                            )
                            ->native(false)
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('professor_id')
                            ->relationship(
                                'professor',
                                'name',
                                fn(Builder $query) => $query->role('professor')
                            )
                            ->native(false)
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('subject_id')
                            ->relationship(
                                'subject',
                                'name',
                                fn(Builder $query) => $query->where('is_active', true)
                            )
                            ->native(false)
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('room_id')
                            ->relationship(
                                'room',
                                'name',
                                fn(Builder $query) => $query->where('is_active', true)
                            )
                            ->native(false)
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->label('Title')
                            ->required(),
                        Forms\Components\TimePicker::make('time')
                            ->required(),
                        Forms\Components\Select::make('semester')
                            ->required()
                            ->native(false)
                            ->options([
                                '1st Semester',
                                '2nd Semester',
                                'Summer'
                            ]),
                        Forms\Components\Select::make('year')
                            ->required()
                            ->options([
                                '2020',
                                '2021',
                                '2022',
                                '2023',
                                '2024',
                                '2025',
                                '2026',
                                '2027',
                                '2028',
                                '2029',
                                '2030',
                            ]),
                        Forms\Components\Toggle::make('is_active')
                            ->required(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('course.name')
                    ->sortable()
                    ->description(fn($record): string => $record->room?->name),
                Tables\Columns\TextColumn::make('professor.name')
                    ->sortable()
                    ->description(fn($record): string => $record->subject?->name),
                Tables\Columns\TextColumn::make('name')
                    ->description(fn($record): string => $record->semester),
                Tables\Columns\TextColumn::make('time')
                    ->description(fn($record): string => 'Year ' . $record->year),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->tooltip('View'),
                    Tables\Actions\EditAction::make()
                        ->tooltip('Edit')
                        ->color('warning'),
                    Tables\Actions\DeleteAction::make()
                        ->label('Archive')
                        ->tooltip('Archive')
                        ->modalHeading('Archive User'),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make()
                        ->color('secondary'),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('Actions')
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
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
