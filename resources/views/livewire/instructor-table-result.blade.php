<div class="w-full">

    <x-filament::section>
        <!-- Filter controls -->
        <div class="mb-4 flex flex-wrap gap-4">
            <!-- Professor filter -->
            <div>
                <label for="professor-filter" class="block text-sm font-medium">Filter by Professor:</label>
                <x-filament::input.wrapper>
                    <x-filament::input.select id="professor-filter" wire:model="selectedProfessor" wire:change="$refresh"
                        class="mt-1 block w-full rounded-md">
                        <option value="">All Professors</option>
                        @foreach ($professors as $professor)
                            <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>

            <!-- Year filter -->
            <div>
                <label for="year-filter" class="block text-sm font-medium">Filter by Year:</label>
                <x-filament::input.wrapper>
                    <x-filament::input.select id="year-filter" wire:model="selectedYear" wire:change="$refresh"
                        class="mt-1 block w-full rounded-md">
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
                        class="mt-1 block w-full rounded-md">
                        <option value="">All Legends</option>
                        @foreach ($allLegends as $code => $description)
                            <option value="{{ $code }}">{{ $code }} - {{ $description }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>
        </div>

        <!-- Existing table markup -->
        <table class="w-full divide-y divide-gray-200">
            <thead class="">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium  uppercase">Legend</th>
                    @if (count($legendDescriptions) > 0)
                        <th class="px-6 py-3 text-left text-xs font-medium  uppercase">Description</th>
                    @endif
                    <th class="px-6 py-3 text-left text-xs font-medium  uppercase">Total Count</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($legendCounts as $legend => $count)
                    <tr>
                        <td class="px-6 py-4">{{ $legend }}</td>
                        @if (count($legendDescriptions) > 0)
                            <td class="px-6 py-4">
                                {{ $legendDescriptions[$legend] ?? 'Unknown' }}
                            </td>
                        @endif
                        <td class="px-6 py-4">{{ $count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-filament::section>
</div>
