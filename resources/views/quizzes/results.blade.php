<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-4">Quiz Results</h1>

            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700">Student Information</h2>
                <p class="text-gray-600">Name: {{ $attempt->student_name }}</p>
                <p class="text-gray-600">Section: {{ $attempt->student_section }}</p>
            </div>

            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700">Score</h2>
                <p class="text-3xl font-bold text-purple-600">
                    {{ $attempt->score }} / {{ $attempt->quiz->questions->count() }}
                </p>
                <p class="text-gray-600">
                    Percentage: {{ number_format(($attempt->score / $attempt->quiz->questions->count()) * 100, 1) }}%
                </p>
            </div>

            @if ($attempt->completion && $attempt->completion->feedback)
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">Feedback</h2>
                    <div class="text-gray-600">
                        <p>Total Questions: {{ $attempt->completion->feedback['total_questions'] }}</p>
                        <p>Correct Answers: {{ $attempt->completion->feedback['correct_answers'] }}</p>
                        <p>Score Percentage: {{ number_format($attempt->completion->feedback['percentage'], 1) }}%</p>
                    </div>
                </div>
            @endif

            <div class="mt-6">
                <a href="{{ route('dashboard') }}"
                    class="inline-block px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
