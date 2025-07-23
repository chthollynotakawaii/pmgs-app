<x-filament::widget class="col-span-full">
    <x-filament::card>
        <div class="space-y-8">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Inventory Overview (Charts)</h2>

            @php
                $statuses = ['Functional', 'Defective', 'Damaged', 'In maintenance'];
                $colors = ['#4CAF50', '#F44336', '#FF9800', '#2196F3'];

                $counts = collect($statuses)->map(fn ($status) =>
                    \App\Models\InventoryRecord::where('status', $status)->count()
                );

                $barData = [
                    'labels' => $statuses,
                    'datasets' => [[
                        'label' => 'Inventory Count by Status',
                        'data' => $counts,
                        'backgroundColor' => $colors,
                        'borderColor' => $colors,
                        'borderWidth' => 1,
                    ]],
                ];

                $lineData = [
                    'labels' => $statuses,
                    'datasets' => [[
                        'label' => 'Inventory Status Over Time',
                        'data' => $counts,
                        'fill' => false,
                        'borderColor' => '#3B82F6',
                        'backgroundColor' => '#3B82F6',
                        'tension' => 0.3,
                    ]],
                ];

                $pieData = [
                    'labels' => $statuses,
                    'datasets' => [[
                        'label' => 'Inventory Breakdown',
                        'data' => $counts,
                        'backgroundColor' => $colors,
                        'borderColor' => '#ffffff',
                        'borderWidth' => 1,
                    ]],
                ];

                $radarData = [
                    'labels' => $statuses,
                    'datasets' => [[
                        'label' => 'Inventory Radar Overview',
                        'data' => $counts,
                        'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                        'borderColor' => 'rgba(59, 130, 246, 1)',
                        'pointBackgroundColor' => 'rgba(59, 130, 246, 1)',
                    ]],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-8">
                <x-chart-box title="Bar Chart" type="bar" :data="$barData" />
                <x-chart-box title="Line Chart" type="line" :data="$lineData" />
                <x-chart-box title="Pie Chart" type="pie" :data="$pieData" />
                <x-chart-box title="Radar Chart" type="radar" :data="$radarData" />
            </div>
        </div>
    </x-filament::card>
</x-filament::widget>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush
