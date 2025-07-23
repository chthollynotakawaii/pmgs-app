<?php

namespace App\Filament\Widgets;

use App\Models\InventoryRecord;
use Filament\Widgets\Widget;

class InventoryOverviewChart extends Widget
{
    protected static string $view = 'filament.widgets.inventory-overview-chart';
    protected static ?int $sort = 5;
    protected static ?string $heading = 'Inventory Overview';
    protected static ?string $maxHeight = '300px';

    public function getData(): array
    {
        $statuses = ['Functional', 'Defective', 'Damaged', 'In maintenance'];

        return [
            'labels' => $statuses,
            'datasets' => [
                [
                    'label' => 'Inventory Status',
                    'data' => array_map(fn ($status) => InventoryRecord::where('status', $status)->count(), $statuses),
                    'backgroundColor' => ['#4CAF50', '#F44336', '#FF9800', '#2196F3'],
                    'borderColor' => ['#4CAF50', '#F44336', '#FF9800', '#2196F3'],
                    'fill' => true,
                ],
            ],
        ];
    }

    public function getRadarData(): array
    {
        $types = ['Functional', 'Defective', 'Damaged', 'In maintenance'];

        return [
            'labels' => $types,
            'datasets' => [
                [
                    'label' => 'Inventory by Type',
                    'data' => array_map(fn ($type) =>
                        InventoryRecord::where('status', $type)->count(), $types),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                ],
            ],
        ];
    }

}
