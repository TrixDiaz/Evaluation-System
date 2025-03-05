<x-filament-panels::page>
    <!-- Profile Section -->
    <x-filament::section>
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                    Profile Information
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    View your account's profile information.
                </p>
            </div>
        </div>

        <div class="mt-6 space-y-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Name Field -->
                <div>
                    <p for="name">Name</p>
                    <x-filament::input.wrapper>
                        <x-filament::input wire:model="name" id="name" type="text" disabled />
                    </x-filament::input.wrapper>
                </div>

                <!-- Email Field -->
                <div>
                    <p for="email">Email</p>
                    <x-filament::input.wrapper>
                        <x-filament::input wire:model="email" id="email" type="email" disabled />
                    </x-filament::input.wrapper>
                </div>
            </div>
        </div>
    </x-filament::section>

    <!-- Schedule Section -->
    {{-- @if (!auth()->check() && auth()->user->hasRole(['professor', 'dean', 'teacher', 'prof', 'admin', 'super_admin'])) --}}
    <x-filament::section>
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                    Schedule Settings
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Update your preferred schedule.
                </p>
            </div>
        </div>

        <div class="mt-6">
            <form wire:submit="updateSchedule" class="space-y-6">
                {{ $this->scheduleForm }}

                <div class="flex justify-end">
                    <x-filament::button type="submit">
                        Update Schedule
                    </x-filament::button>
                </div>
            </form>
        </div>
    </x-filament::section>
    {{-- @endif --}}


    <!-- Password Section -->
    <x-filament::section collapsible collapsed>
        <x-slot name="heading">
            Change Password
        </x-slot>
        <form wire:submit="changePassword" class="space-y-6">
            {{ $this->form }}

            <div class="flex justify-end">
                <x-filament::button type="submit">
                    Change Password
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>
</x-filament-panels::page>
