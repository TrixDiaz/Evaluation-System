<div class="space-y-6 dark:text-gray-100">
    <div class="bg-yellow-50 dark:bg-yellow-900 dark:bg-opacity-30 p-4 rounded-lg">
        <h3 class="font-medium text-yellow-800 dark:text-yellow-200 mb-2">COPUS Instructions:</h3>
        <ul class="list-disc list-inside text-yellow-700 dark:text-yellow-300 space-y-1 text-sm">
            <li>Mark all activities that occur during each 2-minute segment</li>
            <li>Multiple activities can occur in the same time segment</li>
            <li>Hover over activity codes to see full descriptions</li>
            <li>Click on comment boxes to add detailed observations</li>
        </ul>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
        <div>
            <h3 class="font-medium mb-2">Student Activity Codes:</h3>
            <ul class="space-y-1">
                @foreach ($studentActivities as $code => $description)
                    <li><span class="font-medium">{{ $code }}</span> - {{ $description }}</li>
                @endforeach
            </ul>
        </div>
        <div>
            <h3 class="font-medium mb-2">Instructor Activity Codes:</h3>
            <ul class="space-y-1">
                @foreach ($instructorActivities as $code => $description)
                    <li><span class="font-medium">{{ $code }}</span> - {{ $description }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 shadow">
        <table class="w-full border-collapse">
            <!-- Header -->
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-800 text-sm">
                    <th class="border-b border-r border-gray-200 dark:border-gray-700 p-2 text-left w-20 font-medium">
                        min</th>
                    <th colspan="{{ count($studentActivities) }}"
                        class="border-b border-gray-200 dark:border-gray-700 p-2 text-center bg-blue-50 dark:bg-blue-900 font-medium">
                        1. Students doing
                    </th>
                    <th colspan="{{ count($instructorActivities) }}"
                        class="border-b border-gray-200 dark:border-gray-700 p-2 text-center bg-green-50 dark:bg-green-900 font-medium">
                        2. Instructor doing
                    </th>
                    <th rowspan="2"
                        class="border-b border-l border-gray-200 dark:border-gray-700 p-2 text-left font-medium w-72">
                        Comments: <span class="font-normal text-gray-500 dark:text-gray-400">EG: explain difficult
                            concepts, analogies, etc.</span>
                    </th>
                </tr>
                <tr class="bg-gray-50 dark:bg-gray-700 text-xs">
                    <th class="border-r border-b border-gray-200 dark:border-gray-700 p-2 text-center font-medium">Time
                    </th>
                    @foreach (array_keys($studentActivities) as $code)
                        <th class="border-r border-b border-gray-200 dark:border-gray-700 p-2 text-center"
                            title="{{ $studentActivities[$code] }}">
                            {{ $code }}
                        </th>
                    @endforeach
                    @foreach (array_keys($instructorActivities) as $code)
                        <th class="border-r border-b border-gray-200 dark:border-gray-700 p-2 text-center"
                            title="{{ $instructorActivities[$code] }}">
                            {{ $code }}
                        </th>
                    @endforeach
                </tr>
            </thead>

            <!-- Body -->
            <tbody>
                @foreach ($timeSegments as $time)
                    <tr class="hover:bg-gray-400/10 dark:hover:bg-gray-900 hover:dark:text-black transition-colors">
                        <td class="border-r border-b border-gray-200 dark:border-gray-700 p-2 text-center font-medium">
                            {{ $time }}</td>

                        @foreach (array_keys($studentActivities) as $code)
                            <td class="border-r border-b border-gray-200 dark:border-gray-700 p-2 text-center">
                                <input type="checkbox"
                                    name="data[student_activities][{{ $time }}][{{ $code }}]"
                                    value="1"
                                    class="rounded border-gray-300 dark:border-gray-600 text-primary-600 dark:text-primary-400 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 h-4 w-4 dark:bg-gray-700" />
                            </td>
                        @endforeach

                        @foreach (array_keys($instructorActivities) as $code)
                            <td class="border-r border-b border-gray-200 dark:border-gray-700 p-2 text-center">
                                <input type="checkbox"
                                    name="data[instructor_activities][{{ $time }}][{{ $code }}]"
                                    value="1"
                                    class="rounded border-gray-300 dark:border-gray-600 text-primary-600 dark:text-primary-400 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 h-4 w-4 dark:bg-gray-700" />
                            </td>
                        @endforeach

                        <td class="border-b border-gray-200 dark:border-gray-700 p-2">
                            <button type="button" x-data
                                x-on:click="$dispatch('open-modal', { id: 'comment-modal-{{ $time }}' })"
                                class="inline-flex items-center justify-center w-full py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                <span x-data="{ hasComment: false }" x-init="hasComment = $refs.commentInput.value !== ''"
                                    x-effect="hasComment = $refs.commentInput.value !== ''">
                                    <span x-show="!hasComment">Add comment...</span>
                                    <span x-show="hasComment"
                                        x-text="$refs.commentInput.value.length > 40 ? $refs.commentInput.value.substring(0, 37) + '...' : $refs.commentInput.value"></span>
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <textarea x-ref="commentInput" name="data[comments][{{ $time }}]" class="hidden"></textarea>

                            <!-- Modal for each time segment comment -->
                            <div x-data="{ isOpen: false }"
                                x-on:open-modal.window="if ($event.detail.id === 'comment-modal-{{ $time }}') isOpen = true"
                                x-on:close-modal.window="if ($event.detail.id === 'comment-modal-{{ $time }}') isOpen = false"
                                x-on:keydown.escape.window="isOpen = false" x-show="isOpen"
                                class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                                <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
                                    <div x-on:click="isOpen = false"
                                        class="fixed inset-0 transition-opacity bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75">
                                    </div>

                                    <div
                                        class="relative bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full p-6">
                                        <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                                                Comments for {{ $time }} minute segment
                                            </h3>
                                            <div class="mt-4">
                                                <textarea x-on:input="$refs.commentInput.value = $event.target.value"
                                                    class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                                    rows="6" placeholder="Enter detailed observations, explanations, difficulties, analogies, etc."
                                                    x-bind:value="$refs.commentInput.value"></textarea>
                                            </div>
                                        </div>
                                        <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse justify-start gap-6">
                                            <x-filament::button size="md" color="success"
                                                x-on:click="isOpen = false" type="button">
                                                Save Comment
                                            </x-filament::button>
                                            <x-filament::button size="md" color="gray"
                                                x-on:click="isOpen = false" type="button">
                                                Cancel
                                            </x-filament::button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


</div>
