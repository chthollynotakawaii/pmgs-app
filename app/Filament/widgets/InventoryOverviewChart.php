<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\InventoryRecord;
use Illuminate\Support\Carbon;

class InventoryOverviewChart extends ChartWidget
{
    protected static ?string $heading = 'Inventory Overview';
    protected static ?int $sort = 4; // Adjust the sort order as needed
    protected static ?string $maxHeight = '300px'; // Set a maximum height for the chart
    


    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Inventory Status Distribution',
                    'data' => [
                        InventoryRecord::where('status', 'Functional')->count(),
                        InventoryRecord::where('status', 'defective')->count(),
                        InventoryRecord::where('status', 'damaged')->count(),
                        InventoryRecord::where('status', 'in maintenance')->count(),
                    ],
                    'backgroundColor' => [
                        '#4CAF50', // Functional
                        '#F44336', // Defective
                        '#FF9800', // Damaged
                        '#2196F3', // In Maintenance
                    ],
                ],
            ],
            'labels' => ['Functional', 'Defective', 'Damaged', 'In Maintenance'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'x' => [
                    'display' => false,
                ],
                'y' => [
                    'display' => false,
                ],
            ],
        ];
    }
}
