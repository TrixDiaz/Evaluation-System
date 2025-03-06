<x-filament-panels::page>
    <div class="space-y-4">
        <div class="p-6 bg-white rounded-lg shadow">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Respondents</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Responses</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Average Rating</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($this->getSummaryData() as $summary)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap" title="{{ $summary->evaluation_title }}">
                                    {{ Str::words($summary->evaluation_title, 5, '...') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $summary->total_respondents }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $summary->total_responses }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ number_format($summary->average_rating, 2) ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
