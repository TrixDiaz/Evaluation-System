<!-- resources/views/quizzes/results.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen">
    <div class="max-w-3xl mx-auto py-8 px-4">
        <!-- Results Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-purple-600 p-6">
                <h1 class="text-2xl font-bold text-white">{{ $attempt->quiz->title }} - Results</h1>
            </div>

            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-xl font-medium">Your Score</p>
                        <p class="text-3xl font-bold text-purple-600 mt-2">{{ $attempt->score }} points</p>
                    </div>

                    <div class="text-right">
                        <p class="text-sm text-gray-600">Completed on</p>
                        <p class="font-medium">{{ $attempt->completed_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Question Review -->
        @foreach ($attempt->quiz->questions as $index => $question)
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="p-6">
                    <div class="flex">
                        <span class="text-lg font-medium">{{ $index + 1 }}.</span>
                        <div class="ml-3 flex-1">
                            @if (isset($question->question_content['text']))
                                <h2 class="text-lg font-medium">{!! $question->question_content['text'] !!}</h2>
                            @endif

                            <div class="mt-4">
                                @php
                                    $userAnswer = $attempt->answers[$question->id] ?? null;
                                    $isCorrect = false;

                                    // Check if answer is correct (simplified)
                                    if ($question->question_type === 'rating') {
                                        $isCorrect = (int) $userAnswer === $question->rating_correct_answer;
                                    } else {
                                        $isCorrect = $userAnswer == $question->correct_answer;
                                    }
                                @endphp

                                <!-- User's answer -->
                                <div class="mt-2">
                                    <p class="font-medium">Your answer:</p>
                                    <div class="mt-1 p-2 rounded {{ $isCorrect ? 'bg-green-100' : 'bg-red-100' }}">
                                        @switch($question->question_type)
                                            @case('multiple_choice')
                                            @case('checkbox')
                                                {{ $question->answer_content[$userAnswer] ?? 'No answer' }}
                                            @break

                                            @case('text')
                                            @case('textarea')
                                                {{ $userAnswer ?? 'No answer' }}
                                            @break

                                            @case('rating')
                                                Rating: {{ $userAnswer ?? 'No rating' }}
                                            @break
                                        @endswitch
                                    </div>
                                </div>

                                <!-- Correct answer -->
                                <div class="mt-4">
                                    <p class="font-medium">Correct answer:</p>
                                    <div class="mt-1 p-2 rounded bg-green-100">
                                        @switch($question->question_type)
                                            @case('multiple_choice')
                                            @case('checkbox')
                                                {{ $question->answer_content[$question->correct_answer] ?? 'N/A' }}
                                            @break

                                            @case('text')
                                            @case('textarea')
                                                {{ $question->correct_answer }}
                                            @break

                                            @case('rating')
                                                Rating: {{ $question->rating_correct_answer }}
                                            @break
                                        @endswitch
                                    </div>
                                </div>

                                <!-- Points awarded -->
                                <div class="mt-3">
                                    <p class="font-medium">
                                        @if ($isCorrect)
                                            <span class="text-green-600">+{{ $question->points }} points</span>
                                        @else
                                            <span class="text-red-600">0 points</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Actions -->
        <div class="flex space-x-4">
            <a href="{{ route('quiz.show', $attempt->quiz_id) }}"
                class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-md focus:outline-none">
                Retake Quiz
            </a>
            <a href="{{ url('/dashboard') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-md focus:outline-none">
                Back to Dashboard
            </a>
        </div>
    </div>
</body>

</html>
