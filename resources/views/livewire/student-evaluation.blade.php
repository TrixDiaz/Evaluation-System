<div>
    @if ($evaluation->is_active == false || $evaluation->is_active == 0)
        <span class="px-4 py-2 text-gray-600 rounded-lg">Evaluation is closed</span>
    @elseif ($currentStep === 0)
        <span class="px-4 py-2 text-gray-600 rounded-lg">Already Submitted</span>
    @elseif ($currentStep === 99)
        <span class="px-4 py-2 text-gray-600 rounded-lg">Evaluation is closed</span>
    @elseif($currentStep === 1)
        <x-filament::button wire:click="$set('currentStep', 1)">
            Start Evaluation
        </x-filament::button>

        @if ($currentStep === 1)
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-lg max-w-2xl w-full mx-4">
                    <form wire:submit.prevent="nextStep">
                        <div class="space-y-4">
                            <div class="p-4 border rounded-lg bg-gray-50">
                                <h3 class="font-semibold mb-2">Agreement</h3>
                                <p class="text-sm text-gray-600 mb-4">
                                    By proceeding with this evaluation, you agree that all responses will be kept
                                    confidential
                                    and will be used solely for the purpose of improving our educational services.
                                </p>
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" wire:model="agreed" class="rounded border-gray-300">
                                    <span class="text-sm">I agree to the terms and conditions</span>
                                </label>
                                @error('agreed')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <x-filament::button type="button" wire:click="nextStep">
                                Continue
                            </x-filament::button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @elseif ($currentStep === 2)
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Schedule</label>
                    <select wire:model="schedule" class="w-full rounded-lg border-gray-300">
                        <option value="">Select Schedule</option>
                        @foreach ($schedules as $schedule)
                            <option value="{{ $schedule->id }}">
                                {{ $schedule->subject->name }} - {{ $schedule->day }} {{ $schedule->time }}
                            </option>
                        @endforeach
                    </select>
                    @error('schedule')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Year</label>
                    <select wire:model="year" class="w-full rounded-lg border-gray-300">
                        <option value="">Select Year</option>
                        <option value="1">1st Year</option>
                        <option value="2">2nd Year</option>
                        <option value="3">3rd Year</option>
                        <option value="4">4th Year</option>
                    </select>
                    @error('year')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <x-filament::button type="button" wire:click="nextStep">
                Continue to Questions
            </x-filament::button>
        </div>
    @else
        @foreach ($evaluation->questions as $question)
            <div class="space-y-2">
                <label class="block font-medium text-gray-700">
                    {{ $question->question }}
                </label>

                @if ($question->type === 'text')
                    <textarea wire:model="answers.{{ $question->id }}" class="w-full rounded-lg border-gray-300 shadow-sm" rows="3"></textarea>
                @elseif($question->type === 'multiple_choice')
                    @foreach ($question->options as $option)
                        <div class="flex items-center space-x-3">
                            <input type="radio" wire:model="answers.{{ $question->id }}"
                                value="{{ $option['option'] }}" class="border-gray-300">
                            <span>{{ $option['option'] }}</span>
                        </div>
                    @endforeach
                @elseif($question->type === 'rating')
                    <select wire:model="answers.{{ $question->id }}" class="w-full rounded-lg border-gray-300">
                        <option value="">Select rating</option>
                        <option value="Very Poor">Very Poor</option>
                        <option value="Poor">Poor</option>
                        <option value="Fair">Fair</option>
                        <option value="Good">Good</option>
                        <option value="Excellent">Excellent</option>
                    </select>
                @endif
            </div>
        @endforeach

        <div class="mt-6">
            <x-filament::button type="submit">
                Submit Evaluation
            </x-filament::button>
        </div>
    @endif
</div>
