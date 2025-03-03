<x-filament-panels::page>
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
</x-filament-panels::page>
