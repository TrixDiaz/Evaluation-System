<?php

namespace App\Filament\App\Pages;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Filament\Forms\Components\Select;
use App\Models\Schedule;

class CustomProfile extends Page implements HasForms
{
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.app.pages.custom-profile';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Profile';

    public ?string $name = '';
    public ?string $email = '';
    public ?string $current_password = '';
    public ?string $password = '';
    public ?string $password_confirmation = '';

    public ?array $schedule = [];

    protected function getForms(): array
    {
        return [
            'form',
            'scheduleForm',
        ];
    }

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->schedule = $user->scheduleUser->pluck('id')->toArray(); // Changed to scheduleUser
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
        ]);

        $user = Auth::user();
        $user->update($validated);

        Notification::make()
            ->title('Profile updated successfully')
            ->success()
            ->send();
    }

    public function updateSchedule()
    {
        $validated = $this->validate([
            'schedule' => ['required', 'array'],
            'schedule.*' => ['exists:schedules,id'],
        ]);

        $user = Auth::user();
        $now = now();

        $schedules = collect($validated['schedule'])->mapWithKeys(function ($id) use ($now) {
            return [$id => [
                'created_at' => $now,
                'updated_at' => $now,
            ]];
        });

        $user->scheduleUser()->sync($schedules);

        Notification::make()
            ->title('Schedule updated successfully')
            ->success()
            ->send();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('current_password')
                    ->label('Current Password')
                    ->password()
                    ->required()
                    ->currentPassword(),
                TextInput::make('password')
                    ->label('New Password')
                    ->password()
                    ->required()
                    ->rules([
                        PasswordRule::min(8)
                            ->letters()
                            ->numbers()
                            ->symbols()
                    ])
                    ->confirmed(),
                TextInput::make('password_confirmation')
                    ->label('Confirm Password')
                    ->password()
                    ->required(),
            ]);
    }

    public function scheduleForm(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('schedule')
                    ->label('Select Schedule')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->options(Schedule::pluck('name', 'id'))
                    ->required()
            ]);
    }

    public function changePassword(): void
    {
        $validated = $this->validate();
        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        Notification::make()
            ->title('Password updated successfully')
            ->success()
            ->send();
    }
}
