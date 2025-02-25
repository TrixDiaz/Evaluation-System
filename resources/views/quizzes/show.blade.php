<!-- resources/views/quizzes/show.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $quiz->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>

<body class="bg-gray-100 min-h-screen">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Quiz Header -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $quiz->title }}</h1>
                <p class="text-gray-600">{{ $quiz->description }}</p>
            </div>

            <form method="POST" action="{{ route('quiz.submit', $quiz->id) }}" x-data="{
                currentStep: 0,
                totalSteps: 3,
                studentInfo: {
                    name: '',
                    section: ''
                },
                agreed: false,
                answers: {},
                questions: {{ json_encode($quiz->questions) }},
                get isAgreementValid() {
                    return this.agreed;
                },
                get isStudentInfoValid() {
                    return this.studentInfo.name.trim() !== '' && this.studentInfo.section.trim() !== '';
                },
                get areAllQuestionsAnswered() {
                    const totalQuestions = this.questions.length;
                    const answeredQuestions = Object.keys(this.answers).length;
                    return totalQuestions === answeredQuestions;
                },
                get canProceedToNext() {
                    if (this.currentStep === 0) return this.isAgreementValid;
                    if (this.currentStep === 1) return this.isStudentInfoValid;
                    return true;
                },
                get canSubmit() {
                    return this.currentStep === 2 && this.areAllQuestionsAnswered;
                }
            }">
                @csrf

                <!-- Step 1: Agreement -->
                <div x-show.transition.opacity="currentStep === 0" class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Terms and Conditions</h2>
                    <p class="text-gray-600 mb-4">Please read and agree to the following terms before proceeding with
                        the quiz:</p>
                    <ul class="list-disc pl-5 mb-6 text-gray-600">
                        <li>You must complete the quiz in one session</li>
                        <li>You must answer all questions</li>
                        <li>Your responses will be recorded and evaluated</li>
                    </ul>
                    <div class="flex items-center mb-4">
                        <input type="checkbox" id="agreement" x-model="agreed"
                            class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                        <label for="agreement" class="ml-2 text-gray-700">I agree to the terms and conditions</label>
                    </div>
                </div>

                <!-- Step 2: Student Information -->
                <div x-show.transition.opacity="currentStep === 1" class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Student Information</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" x-model="studentInfo.name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Section</label>
                            <input type="text" x-model="studentInfo.section"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                        </div>
                    </div>
                </div>

                <!-- Step 3: Questions -->
                <div x-show.transition.opacity="currentStep === 2">
                    @foreach ($quiz->questions as $index => $question)
                        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                            <div class="flex items-start">
                                <span class="text-lg font-medium mr-3">{{ $index + 1 }}.</span>
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium mb-4">
                                        @if (is_array($question->question_content) && !empty($question->question_content[0]))
                                            {!! $question->question_content[0]['data']['question_text'] ?? '' !!}
                                        @endif
                                    </h3>

                                    <!-- Question Types -->
                                    @switch($question->question_type)
                                        @case('rating')
                                            <div class="flex space-x-4">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <label class="flex items-center">
                                                        <input type="radio" name="answers[{{ $question->id }}]"
                                                            value="{{ $i }}" x-model="answers[{{ $question->id }}]"
                                                            class="rounded-full border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                                        <span class="ml-2">{{ $i }} -
                                                            {{ ['Very Poor', 'Poor', 'Average', 'Good', 'Excellent'][$i - 1] }}</span>
                                                    </label>
                                                @endfor
                                            </div>
                                        @break

                                        @case('text')
                                            <div class="mt-2">
                                                <input type="text" name="answers[{{ $question->id }}]"
                                                    x-model="answers[{{ $question->id }}]"
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                                    placeholder="Type your answer here...">
                                            </div>
                                        @break

                                        @case('multiple_choice')
                                            <div class="space-y-2">
                                                @if (isset($question->answer_content[0]['multiple_choice']['choices']))
                                                    @foreach ($question->answer_content[0]['multiple_choice']['choices'] as $index => $choice)
                                                        <label
                                                            class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                                            <input type="radio" name="answers[{{ $question->id }}]"
                                                                value="{{ $index }}"
                                                                x-model="answers[{{ $question->id }}]"
                                                                class="rounded-full border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                                            <span class="ml-2">{{ $choice['option_text'] }}</span>
                                                        </label>
                                                    @endforeach
                                                @else
                                                    <!-- Fallback for older format or direct options -->
                                                    @foreach ($question->answer_content ?? [] as $index => $choice)
                                                        <label
                                                            class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                                            <input type="radio" name="answers[{{ $question->id }}]"
                                                                value="{{ $index }}"
                                                                x-model="answers[{{ $question->id }}]"
                                                                class="rounded-full border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                                            <span
                                                                class="ml-2">{{ is_array($choice) ? $choice['text'] ?? ($choice['option_text'] ?? '') : $choice }}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                        @break
                                    @endswitch

                                    <!-- Question Image (if exists) -->
                                    @if (isset($question->question_content[0]['type']) && $question->question_content[0]['type'] === 'image_question')
                                        <div class="mt-4">
                                            <img src="{{ asset($question->question_content[0]['data']['image']) }}"
                                                alt="Question Image" class="max-w-full rounded-lg">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Questions Progress -->
                    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Questions Answered:</span>
                            <span class="text-sm font-medium"
                                x-text="Object.keys(answers).length + ' of ' + questions.length"></span>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between mt-6">
                    <button type="button" x-show="currentStep > 0" @click="currentStep--"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                        Previous
                    </button>
                    <div class="ml-auto">
                        <button type="button" x-show="currentStep < 2" @click="if(canProceedToNext) currentStep++"
                            x-bind:disabled="!canProceedToNext"
                            x-bind:class="{ 'opacity-50 cursor-not-allowed': !canProceedToNext }"
                            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                            Next
                        </button>
                        <button type="submit" x-show="currentStep === 2" x-bind:disabled="!canSubmit"
                            x-bind:class="{ 'opacity-50 cursor-not-allowed': !canSubmit }"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                            Submit Quiz
                        </button>
                    </div>
                </div>
            </form>

            <!-- Quiz Footer -->
            @if ($quiz->footer_content)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mt-6">
                    <div class="prose dark:prose-invert">
                        {!! $quiz->footer_content['content'] ?? '' !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>

</html>
