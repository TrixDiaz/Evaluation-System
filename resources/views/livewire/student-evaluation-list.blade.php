<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Available Evaluations</h2>

    <div class="grid gap-4">
        @foreach ($evaluations as $evaluation)
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $evaluation->title }}</h3>
                        <p class="text-gray-600">{{ $evaluation->description }}</p>
                    </div>
                    <a href="{{ route('student.evaluation', $evaluation) }}"
                        class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                        Start Evaluation
                    </a>
                </div>
            </div>
        @endforeach

        @if ($evaluations->isEmpty())
            <div class="text-center py-8 text-gray-500">
                No evaluations available at this time.
            </div>
        @endif
    </div>
</div>
