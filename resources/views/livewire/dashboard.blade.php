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

        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 998;
        }

        .custom-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 999;
            display: none;
            width: 90%;
            max-width: 400px;
        }

        .modal-header {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #1f2937;
        }

        .modal-content {
            margin-bottom: 1.5rem;
            color: #4b5563;
        }

        .modal-button {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            background-color: rgb(16, 185, 129);
            color: white;
            text-align: center;
            border-radius: 0.375rem;
            font-weight: 500;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .modal-button:hover {
            background-color: rgb(4, 120, 87);
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
                {{-- <livewire:student-activity-result /> --}}
                <livewire:student-table-result>
                    <livewire:instructor-table-result>
                        {{-- <livewire:instructor-activity-result /> --}}
            </div>
        </section>
    @else
        <section class="space-y-4">

            @php
                $needsScheduleUpdate =
                    !auth()
                        ->user()
                        ->hasRole(['super_admin', 'admin', 'professor']) &&
                    (auth()->user()->user_schedule === null || empty(auth()->user()->user_schedule));
            @endphp

            @if ($needsScheduleUpdate)
                <div id="modalBackdrop" class="modal-backdrop"></div>
                <div id="scheduleModal" class="custom-modal">
                    <div class="modal-header">
                        Update Required
                    </div>
                    <div class="modal-content">
                        <p>Please update your schedule information to continue.</p>
                    </div>
                    <a href="{{ route('filament.app.pages.custom-profile') }}" class="modal-button">
                        Update Schedule
                    </a>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const modal = document.getElementById('scheduleModal');
                        const backdrop = document.getElementById('modalBackdrop');

                        modal.style.display = 'block';
                        backdrop.style.display = 'block';
                    });
                </script>
            @endif
            <x-filament::section>
                <x-slot name="heading">
                    <div class="text-2xl font-bold">
                        Welcome, {{ auth()->user()->name }}
                    </div>
                </x-slot>

                <div class="flex flex-row items-center justify-center gap-4 p-6 rounded-xl shadow-lg">
                    <x-filament::button outlined icon="heroicon-m-user-circle" size="xl"
                        href="{{ route('filament.app.pages.custom-profile') }}" tag="a" target="_blank"
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
