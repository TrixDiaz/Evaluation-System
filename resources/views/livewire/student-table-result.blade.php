<div class="w-full overflow-x-auto">
    <table class="w-full min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Legend</th>
                @if (count($legendDescriptions) > 0)
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                @endif
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Count</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach ($legendCounts as $legend => $count)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $legend }}</td>
                    @if (count($legendDescriptions) > 0)
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $legendDescriptions[$legend] ?? 'Unknown' }}
                        </td>
                    @endif
                    <td class="px-6 py-4 whitespace-nowrap">{{ $count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
