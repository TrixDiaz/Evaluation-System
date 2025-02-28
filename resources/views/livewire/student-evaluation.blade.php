<div>
    <form wire:submit.prevent="submit" class="space-y-6">
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
                            <input type="radio" wire:model="answers.{{ $question->id }}" value="{{ $option['option'] }}"
                                class="border-gray-300">
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
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-white hover:bg-primary-700">
                Submit Evaluation
            </button>
        </div>

        @if (session()->has('message'))
            <div class="mt-4 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('message') }}
            </div>
        @endif
    </form>
</div>
