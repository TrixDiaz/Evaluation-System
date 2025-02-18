<x-filament::section>
    <div class="space-y-6">
        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow">
            <table class="w-full border-collapse">
                <!-- Header -->
                <thead>
                <tr class="bg-gray-100 text-sm">
                    <th class="border-b border-r border-gray-200 p-2 text-left w-20 font-medium">min</th>
                    <th colspan="{{ count($studentActivities) }}" class="border-b border-gray-200 p-2 text-center bg-blue-50 font-medium">
                        1. Students doing
                    </th>
                    <th colspan="{{ count($instructorActivities) }}" class="border-b border-gray-200 p-2 text-center bg-green-50 font-medium">
                        2. Instructor doing
                    </th>
                    <th rowspan="2" class="border-b border-l border-gray-200 p-2 text-left font-medium w-72">
                        Comments: <span class="font-normal text-gray-500">EG: explain difficult concepts, analogies, etc.</span>
                    </th>
                </tr>
                <tr class="bg-gray-50 text-xs">
                    <th class="border-r border-b border-gray-200 p-2 text-center font-medium">Time</th>
                    @foreach(array_keys($studentActivities) as $code)
                        <th class="border-r border-b border-gray-200 p-2 text-center" title="{{ $studentActivities[$code] }}">
                            {{ $code }}
                        </th>
                    @endforeach
                    @foreach(array_keys($instructorActivities) as $code)
                        <th class="border-r border-b border-gray-200 p-2 text-center" title="{{ $instructorActivities[$code] }}">
                            {{ $code }}
                        </th>
                    @endforeach
                </tr>
                </thead>

                <!-- Body -->
                <tbody>
                @foreach($timeSegments as $time)
                    <tr class="hover:bg-gray-50">
                        <td class="border-r border-b border-gray-200 p-2 text-center font-medium">{{ $time }}</td>

                        @foreach(array_keys($studentActivities) as $code)
                            <td class="border-r border-b border-gray-200 p-2 text-center">
                                <input type="checkbox" name="student_activities[{{ $time }}][{{ $code }}]" value="1"
                                       class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 h-4 w-4" />
                            </td>
                        @endforeach

                        @foreach(array_keys($instructorActivities) as $code)
                            <td class="border-r border-b border-gray-200 p-2 text-center">
                                <input type="checkbox" name="instructor_activities[{{ $time }}][{{ $code }}]" value="1"
                                       class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 h-4 w-4" />
                            </td>
                        @endforeach

                        <td class="border-b border-gray-200 p-2">
                            <input type="text" name="comments[{{ $time }}]" placeholder="Enter observations for this time segment..."
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 text-sm" />
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="bg-yellow-50 p-4 rounded-lg">
            <h3 class="font-medium text-yellow-800 mb-2">COPUS Instructions:</h3>
            <ul class="list-disc list-inside text-yellow-700 space-y-1 text-sm">
                <li>Mark all activities that occur during each 2-minute segment</li>
                <li>Multiple activities can occur in the same time segment</li>
                <li>Hover over activity codes to see full descriptions</li>
            </ul>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm bg-gray-50 p-4 rounded-lg">
            <div>
                <h3 class="font-medium mb-2">Student Activity Codes:</h3>
                <ul class="space-y-1">
                    @foreach($studentActivities as $code => $description)
                        <li><span class="font-medium">{{ $code }}</span> - {{ $description }}</li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h3 class="font-medium mb-2">Instructor Activity Codes:</h3>
                <ul class="space-y-1">
                    @foreach($instructorActivities as $code => $description)
                        <li><span class="font-medium">{{ $code }}</span> - {{ $description }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

</x-filament::section>
