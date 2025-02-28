<x-filament-panels::page>
    <div class="space-y-6">
        @if ($evaluations->count() > 0)
            @foreach ($evaluations as $evaluation)
                <div class="p-6 bg-white rounded-lg shadow">
                    <h2 class="text-2xl font-bold mb-4">{{ $evaluation->title }}</h2>
                    <p class="mb-4 text-gray-600">{{ $evaluation->description }}</p>

                    <livewire:student-evaluation :evaluation="$evaluation" :wire:key="'evaluation-'.$evaluation->id" />
                </div>
            @endforeach
        @else
            <div class="text-center text-gray-500">
                No evaluations available.
            </div>
        @endif
    </div>
</x-filament-panels::page>
