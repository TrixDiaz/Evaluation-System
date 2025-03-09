<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\UserResource\Pages;
use App\Filament\App\Resources\UserResource\RelationManagers;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource implements HasShieldPermissions
{
    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'publish'
        ];
    }

    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'System Settings';
    protected static ?string $navigationLabel = 'User';
    protected static ?string $navigationIcon = 'heroicon-o-finger-print';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $navigationBadgeTooltip = 'The number of users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Full Name')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'unique' => 'Email has already been registered.',
                            ])
                            ->required(),
                        Forms\Components\Placeholder::make('email_verified_at')
                            ->label('Email Verified')
                            ->content(fn(User $record) => $record->email_verified_at === null ? 'Pending' : $record->email_verified_at->toFormattedDateString())
                            ->visibleOn('view'),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required()
                            ->confirmed()
                            ->visibleOn('create'),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->password()
                            ->required()
                            ->visibleOn('create'),
                    ])->columns(2),
                Forms\Components\Section::make('Other Information')
                    ->description('Roles and Permission with Dean List of Professor Section you can choose multiple.')
                    ->schema([
                        Forms\Components\Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()

                            ->searchable(),
                        Forms\Components\Toggle::make('email_verified_at')
                            ->label('Verified Email')
                            ->onIcon('heroicon-m-bolt')
                            ->offIcon('heroicon-m-user')
                            ->visibleOn('create')
                            ->dehydrateStateUsing(fn($state) => $state ? now() : null),
                        Forms\Components\TextInput::make('parent_id')
                            ->label('Parent Users')
                            ->default(fn() => auth()->id())
                            ->hidden(fn() => auth()->user()->cannot('assign_parent_user')),
                        Forms\Components\Select::make('children')
                            ->label('Sub Professors')
                            ->relationship(
                                'children',
                                'name',
                                fn(Builder $query) => $query->whereHas('roles', fn($q) => $q->where('name', 'professor'))
                            )
                            ->multiple()
                            ->preload()
                            ->searchable(),

                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Full Name')
                    ->searchable(['name'])
                    ->description(fn($record): string => $record->roles->pluck('name')->implode(', ') ?: 'Student')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->icon('heroicon-m-envelope')
                    ->tooltip('Click to Copy')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('children.name')
                    ->label('Professor')
                    ->badge()
                    ->listWithLineBreaks()
                    ->limitList(1)
                    ->expandableLimitedList()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('verified_date')
                    ->label('Verified Date')
                    ->default(fn(User $record) => $record->email_verified_at === null ? 'Pending' : $record->email_verified_at->format('M d, Y'))
                    ->colors([
                        'success' => fn(User $record) => $record->email_verified_at !== null,
                        'warning' => fn(User $record) => $record->email_verified_at === null,
                    ])
                    ->badge()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created Date')
                    ->dateTime('m/d/Y')
                    ->tooltip('Month/Day/Year')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Update')
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //....
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
            ])->poll('30s');
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
