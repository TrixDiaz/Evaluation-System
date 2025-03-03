<div class="p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100">
        Available Evaluations
    </h2>

    <div class="grid gap-4">
        @foreach ($evaluations as $evaluation)
            <div class="p-4 rounded-lg shadow bg-white dark:bg-gray-800">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {{ $evaluation->title }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ $evaluation->description }}
                        </p>
                    </div>
                    <a href="{{ route('student.evaluation', $evaluation) }}"
                        class="px-4 py-2 rounded-lg text-white bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-400">
                        Start Evaluation
                    </a>
                </div>
            </div>
        @endforeach

        @if ($evaluations->isEmpty())
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                No evaluations available at this time.
            </div>
        @endif
    </div>
</div>
