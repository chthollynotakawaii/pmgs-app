<x-filament::widget class="col-span-full">
    <x-filament::card>
        <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">
            Upcoming Maintenance (Next 30 Days)
        </h2>

        <div class="w-full overflow-x-auto">
            <table class="min-w-full w-full table-auto text-sm text-left text-gray-900 dark:text-white">
                <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-800 dark:text-white">
                    <tr>
                        <th class="px-4 py-3 w-1/3">Maintenance Type</th>
                        <th class="px-4 py-3 w-1/3">Scheduled Date</th>
                        <th class="px-4 py-3 w-1/3">Inventory Items</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                    @forelse ($this->getData()['maintenances'] as $maintenance)
                        <tr>
                            <td class="px-4 py-2 align-top">{{ $maintenance->maintenance_type }}</td>
                            <td class="px-4 py-2 align-top">{{ $maintenance->scheduled_date->format('F d, Y') }}</td>
                            <td class="px-4 py-2 align-top">
                                {{ implode(', ', \App\Models\InventoryRecord::whereIn('id', $maintenance->inventory_record_ids)->pluck('temp_serial')->toArray()) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-4 text-center text-gray-500 dark:text-white">
                                No upcoming maintenance.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::card>
</x-filament::widget>
