<div class="w-full overflow-x-auto">
    <x-filament::section>
        <!-- Filter controls -->
        <div class="mb-4 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3">
            <!-- Professor filter -->
            <div>
                <label for="professor-filter" class="block text-sm font-medium">Filter by Student:</label>
                <x-filament::input.wrapper>
                    <x-filament::input.select id="professor-filter" wire:model="selectedProfessor" wire:change="$refresh"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">All Professors</option>
                        @foreach ($professors as $professor)
                            <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>
            <!-- Year filter -->
            <div>
                <label for="year-filter" class="block text-sm font-medium">Filter by School Year:</label>
                <x-filament::input.wrapper>
                    <x-filament::input.select id="year-filter" wire:model="selectedYear" wire:change="$refresh"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">All Years</option>
                        @foreach ($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>
            <!-- Legend filter -->
            <div>
                <label for="legend-filter" class="block text-sm font-medium">Filter by Legend:</label>
                <x-filament::input.wrapper>
                    <x-filament::input.select id="legend-filter" wire:model="selectedLegend" wire:change="$refresh"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">All Legends</option>
                        @foreach ($allLegends as $code => $description)
                            <option value="{{ $code }}">{{ $code }} - {{ $description }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>
        </div>

        <!-- Results table -->
        <table class="w-full min-w-full divide-y ">
            <thead class="">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium  uppercase">Legend</th>
                    @if (count($legendDescriptions) > 0)
                        <th class="px-6 py-3 text-left text-xs font-medium  uppercase">Description</th>
                    @endif
                    <th class="px-6 py-3 text-left text-xs font-medium  uppercase">Total Count</th>
                </tr>
            </thead>
            <tbody class="divide-y ">
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
    </x-filament::section>
</div>
