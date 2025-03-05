<x-filament-panels::page>

    <x-filament::section>
        {{-- Statistics Section --}}
        <div class="mb-6 grid grid-cols-3 gap-4">
            <div class="p-4 border border-gray-600 rounded-lg shadow">
                <h4 class="text-sm font-medium">Total Evaluations</h4>
                <p class="text-2xl font-bold">{{ $totalEvaluations }}</p>
            </div>
            <div class="p-4 border border-gray-600 rounded-lg shadow">
                <h4 class="text-sm font-medium">Completed</h4>
                <p class="text-2xl font-bold text-green-600">{{ $completedEvaluations }}</p>
            </div>
            <div class="p-4 border border-gray-600 rounded-lg shadow">
                <h4 class="text-sm font-medium">Completion Rate</h4>
                <p class="text-2xl font-bold text-blue-600">{{ $completionPercentage }}%</p>
            </div>
        </div>
    </x-filament::section>

    <x-filament::section>
        {{-- Student Evaluation Starting Line --}}
        <div class="space-y-4">
            @foreach ($evaluations as $evaluation)
                <div class="p-4 rounded-lg shadow">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold">{{ $evaluation->title }}</h3>
                            <p class="text-gray-600">{{ $evaluation->description }}</p>
                        </div>
                        @if ($this->hasSubmitted($evaluation->id))
                            <x-filament::button disabled class="opacity-50 cursor-not-allowed">
                                Already Submitted
                            </x-filament::button>
                        @else
                            <x-filament::button wire:click="startEvaluation({{ $evaluation->id }})">
                                Start Evaluation
                            </x-filament::button>
                        @endif
                    </div>
                </div>
            @endforeach

            @if ($evaluations->isEmpty())
                <div class="text-center py-8 text-gray-500">
                    No evaluations available at this time.
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-panels::page>
