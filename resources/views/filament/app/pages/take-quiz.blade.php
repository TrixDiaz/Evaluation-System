{{-- resources/views/filament/app/pages/take-quiz.blade.php --}}
<x-filament-panels::page>
    <form wire:submit="submit">
        {{-- Quiz Title and Description --}}
        <div class="mb-6">
            <h2 class="text-2xl font-bold mb-2">{{ $this->quiz->title }}</h2>
            @if($this->quiz->description)
                <p class="text-gray-600">{{ $this->quiz->description }}</p>
            @endif
        </div>

        {{-- Time Limit Warning if set --}}
        @if($this->quiz->time_limit)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Time limit: {{ $this->quiz->time_limit }} minutes
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Header Content --}}
        <div class="mb-6 prose max-w-none">
            @if($this->quiz->header_content)
                @foreach($this->quiz->header_content as $block)
                    @if($block['type'] === 'heading')
                        <{{ $block['data']['level'] }} class="mt-4 mb-2">
                        {{ $block['data']['text'] }}
        </{{ $block['data']['level'] }}>
        @elseif($block['type'] === 'paragraph')
            <div class="mb-4">
                {!! $block['data']['content'] !!}
            </div>
            @endif
            @endforeach
            @endif
            </div>

            {{-- Questions Form --}}
            {{ $this->form }}

            {{-- Footer Content --}}
            <div class="mt-6 prose max-w-none">
                @if($this->quiz->footer_content)
                    @foreach($this->quiz->footer_content as $block)
                        @if($block['type'] === 'text')
                            <div class="mb-4">
                                {!! $block['data']['content'] !!}
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>

            {{-- Submit Button --}}
            <div class="mt-6">
                <x-filament::button
                    type="submit"
                    size="lg"
                    class="w-full md:w-auto"
                >
                    Submit Quiz
                </x-filament::button>
            </div>
    </form>
</x-filament-panels::page>
