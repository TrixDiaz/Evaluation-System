<div class="max-w-4xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">{{ $evaluation->title }}</h1>
    <p class="mb-6">{{ $evaluation->description }}</p>

    <form wire:submit.prevent="submit">
        @foreach ($evaluation->questions as $question)
            <div class="mb-6">
                <label class="block mb-2 font-semibold">{{ $question->question }}</label>

                @if ($question->type === 'text')
                    <textarea wire:model="answers.{{ $question->id }}" class="w-full rounded-lg border-gray-300" rows="3"></textarea>
                @elseif($question->type === 'multiple_choice')
                    @foreach ($question->options as $option)
                        <div class="flex items-center mb-2">
                            <input type="radio" wire:model="answers.{{ $question->id }}"
                                value="{{ $option['option'] }}" class="mr-2">
                            <span>{{ $option['option'] }}</span>
                        </div>
                    @endforeach
                @elseif($question->type === 'rating')
                    <select wire:model="answers.{{ $question->id }}" class="rounded-lg border-gray-300">
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

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
            Submit Evaluation
        </button>
    </form>
</div>
