<x-filament::widget>
    <x-filament::card>
        <div class="space-y-8 w-full">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">UNIFORM INVENTORY SUMMARY</h2>

            @foreach ($grouped as $courseName => $rows)
                <div class="space-y-4 w-full">
                    <h3 class="text-lg font-bold text-blue-600 dark:text-blue-400">{{ $courseName }}</h3>

                    {{-- Desktop Table View --}}
                    <div class="hidden md:block">
                        <table class="w-full table-auto text-sm divide-y divide-gray-200 dark:divide-gray-700 text-gray-800 dark:text-gray-200">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="text-left px-4 py-2 font-semibold">Uniform Type</th>
                                    <th class="text-left px-4 py-2 font-semibold">Size</th>
                                    <th class="text-right px-4 py-2 font-semibold">Total</th>
                                    <th class="text-right px-4 py-2 font-semibold">Distributed</th>
                                    <th class="text-right px-4 py-2 font-semibold">Remaining</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rows as $row)
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="px-4 py-2">{{ $row['uniform_type'] }}</td>
                                        <td class="px-4 py-2">{{ $row['size'] }}</td>
                                        <td class="px-4 py-2 text-right">{{ $row['total'] }}</td>
                                        <td class="px-4 py-2 text-right">{{ $row['distributed'] }}</td>
                                        <td class="px-4 py-2 text-right font-bold {{ $row['remaining'] == 0 ? 'text-red-500' : '' }}">
                                            {{ $row['remaining'] }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-2 italic text-gray-500 dark:text-gray-400">
                                            No records available.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Accordion View --}}
                    <div class="space-y-2 md:hidden">
                        @forelse ($rows as $row)
                            <div class="border rounded-lg shadow-sm bg-white dark:bg-gray-900 dark:border-gray-700">
                                <div class="p-4 space-y-1">
                                    <div class="font-semibold text-gray-900 dark:text-white">{{ $row['uniform_type'] }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-300">Size: <span class="font-medium">{{ $row['size'] }}</span></div>
                                    <div class="text-sm text-gray-600 dark:text-gray-300">Total: <span class="font-medium">{{ $row['total'] }}</span></div>
                                    <div class="text-sm text-gray-600 dark:text-gray-300">Distributed: <span class="font-medium">{{ $row['distributed'] }}</span></div>
                                    <div class="text-sm font-bold {{ $row['remaining'] == 0 ? 'text-red-500' : 'text-gray-900 dark:text-white' }}">
                                        Remaining: {{ $row['remaining'] }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-sm italic text-gray-500 dark:text-gray-400 border rounded-lg">
                                No records available.
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::card>
</x-filament::widget>
