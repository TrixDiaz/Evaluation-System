<x-filament-panels::page>
    <x-filament::section>
        <div class="mb-4">
            <h2 class="text-2xl font-bold">{{ $evaluation->title }}</h2>
            <p class="text-gray-600">{{ $evaluation->description }}</p>
        </div>

        <div class="rounded-lg shadow p-6">
            <form wire:submit.prevent="submit">
                <!-- Step 1: Agreement -->
                @if ($currentStep === 1)
                    <div class="space-y-4">
                        <div class="p-4 border rounded-lg">
                            <h3 class="font-semibold mb-2">Agreement</h3>
                            <p class="text-sm text-gray-600 mb-4">
                                By proceeding with this evaluation, you agree that all responses will be kept
                                confidential
                                and will be used solely for the purpose of improving our educational services.
                            </p>
                            <label class="flex items-center space-x-2 gap-2">
                                <input type="checkbox" wire:model="agreed" class="rounded border-gray-300">
                                <span class="text-sm">I agree to the terms and conditions</span>
                            </label>
                            @error('agreed')
                                <span style="color:red" class="text-danger text-sm block mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <x-filament::button type="button" wire:click="nextStep">
                                Continue
                            </x-filament::button>
                        </div>
                    </div>

                    <!-- Step 2: Schedule and Year -->
                @elseif ($currentStep === 2)
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium mb-1">Schedule</label>
                                <div class="p-2 border rounded-md">
                                    @if ($schedule)
                                        @php
                                            $selectedSchedule = \App\Models\Schedule::find($schedule);
                                        @endphp
                                        Schedule ID: {{ $selectedSchedule->id }} -
                                        {{ $selectedSchedule->subject->name }} - {{ $selectedSchedule->day }}
                                        {{ $selectedSchedule->time }}
                                    @else
                                        No schedule selected
                                    @endif
                                </div>
                                <input type="hidden" wire:model="schedule">
                                @error('schedule')
                                    <span style="color:red" class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block font-medium  mb-1">Year</label>
                                <x-filament::input.wrapper>
                                    <x-filament::input.select wire:model="year"
                                        class="w-full rounded-lg border-gray-300">
                                        <option value="">Select Year</option>
                                        <option value="1">1st Year</option>
                                        <option value="2">2nd Year</option>
                                        <option value="3">3rd Year</option>
                                        <option value="4">4th Year</option>
                                    </x-filament::input.select>
                                </x-filament::input.wrapper>
                                @error('year')
                                    <span style="color:red" class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <x-filament::button type="button" wire:click="previousStep">
                                Back
                            </x-filament::button>
                            <x-filament::button type="button" wire:click="nextStep">
                                Continue to Questions
                            </x-filament::button>
                        </div>
                    </div>

                    <!-- Step 3: Questions -->
                @else
                    <div class="space-y-6">
                        @foreach ($evaluation->questions as $question)
                            <div class="space-y-2">
                                <label class="block font-medium ">
                                    {{ $question->question }}
                                </label>

                                @if ($question->type === 'text')
                                    <x-filament::input.wrapper>
                                        <x-filament::input wire:model="answers.{{ $question->id }}"
                                            class="w-full rounded-lg border-gray-300" rows="3" />
                                    </x-filament::input.wrapper>
                                @elseif($question->type === 'multiple_choice')
                                    @foreach (is_array($question->options) ? $question->options : [] as $option)
                                        <div class="flex items-center space-x-3">
                                            <input type="radio" wire:model="answers.{{ $question->id }}"
                                                value="{{ is_array($option) ? $option['option'] ?? '' : $option }}"
                                                class="border-gray-300 mx-2">
                                            <span class="mx-2">
                                                {{ is_array($option) ? $option['option'] ?? '' : $option }}</span>
                                        </div>
                                    @endforeach
                                @elseif($question->type === 'rating')
                                    <x-filament::input.wrapper>
                                        <x-filament::input.select wire:model="answers.{{ $question->id }}"
                                            class="w-full rounded-lg border-gray-300">
                                            <option value="">Select rating</option>
                                            <option value="1">1 - Very Poor</option>
                                            <option value="2">2 - Poor</option>
                                            <option value="3">3 - Fair</option>
                                            <option value="4">4 - Good</option>
                                            <option value="5">5 - Excellent</option>
                                        </x-filament::input.select>
                                    </x-filament::input.wrapper>
                                @endif

                                @error('answers.' . $question->id)
                                    <span style="color:red" class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        @endforeach

                        <div class="flex justify-between pt-6">
                            <x-filament::button type="button" wire:click="previousStep">
                                Back
                            </x-filament::button>
                            <x-filament::button type="submit">
                                Submit Evaluation
                            </x-filament::button>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </x-filament::section>
</x-filament-panels::page>
