<x-filament::widget>
    <x-filament::card>
        <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Overdue Borrowed Items</h2>

        @php $overdues = $this->getData()['overdues']; @endphp

        @if ($overdues->isEmpty())
            <p class="text-sm text-gray-600 dark:text-gray-300">No overdue borrowed items.</p>
        @else
            <ul class="list-disc list-inside space-y-2 text-sm text-red-600 dark:text-red-400">
                @foreach ($overdues as $log)
                    <li>
                        {{ $log->inventoryRecord->temp_serial ?? 'Unknown Serial' }}
                        — should’ve been returned by
                        <strong>{{ \Carbon\Carbon::parse($log->returned_at)->format('F d, Y') }}</strong>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-filament::card>
</x-filament::widget>
