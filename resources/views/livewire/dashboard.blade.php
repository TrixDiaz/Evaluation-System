<div>
    <style>
        .custom-button {
            transition: all 0.2s ease-in-out;
        }

        .custom-button:hover {
            background-color: rgb(16, 185, 129) !important;
            color: white !important;
        }

        .gray-button:hover {
            background-color: rgb(14, 165, 233) !important;
            color: white !important;
        }
    </style>

    @if (auth()->user()->hasRole(['super_admin', 'admin ']))
        <section>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <livewire:student-activities />
                <livewire:instructor-activities />
            </div>
        </section>
        <section class="mt-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <livewire:student-activity-result />
                <livewire:instructor-activity-result />
            </div>
        </section>
    @else
        <section>
            <x-filament::section>
                <x-slot name="heading">
                    <div class="text-2xl font-bold">
                        Welcome, {{ auth()->user()->name }}
                    </div>
                </x-slot>

                <div class="flex flex-row items-center justify-center gap-4 p-6 rounded-xl shadow-lg">
                    <x-filament::button outlined icon="heroicon-m-user-circle" size="xl"
                        href="{{ route('filament.app.auth.profile') }}" tag="a" target="_blank"
                        class="w-64 h-16 text-lg justify-center custom-button">
                        Profile
                    </x-filament::button>

                    <x-filament::button outlined icon="heroicon-m-book-open" size="xl" color="secondary"
                        href="{{ route('filament.app.pages.student-evaluation') }}" tag="a" target="_blank"
                        class="w-64 h-16 text-lg justify-center gray-button">
                        Student Evaluation
                    </x-filament::button>
                </div>
            </x-filament::section>
        </section>
    @endif


</div>
