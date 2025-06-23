<?php

namespace App\Filament\Widgets;

use App\Models\InventoryRecord;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatusCard extends BaseWidget
{
    protected static ?int $sort = 1; // Adjust the sort order as needed
    protected ?string $heading = 'Inventory Status Overview';

    protected function getStats(): array
    {
        $query = InventoryRecord::query();
        $chartData = []; // You can populate this as needed

        return [
            Stat::make('Total Inventory', (clone $query)->count())
                ->description('Total number of registered inventory items')
                ->color('primary')
                ->chart($chartData)
                ->icon('heroicon-o-home'),

            Stat::make('Total Borrowed Items', (clone $query)->where('borrowed', true)->count())
                ->description('Total number of items currently borrowed')
                ->color('info')
                ->icon('heroicon-o-clipboard'),

            Stat::make('Total Defective Items', (clone $query)->where('status', 'defective')->count())
                ->description('Total number of defective inventory items')
                ->color('danger')
                ->icon('heroicon-o-exclamation-triangle'),

            Stat::make('Total Functional Items', (clone $query)->where('status', 'functional')->count())
                ->description('Total number of functional inventory items')
                ->color('success')
                ->icon('heroicon-o-check-circle'),

            Stat::make('Total Items Damaged', (clone $query)->where('status', 'damaged')->count())
                ->description('Total number of damaged inventory items')
                ->color('danger')
                ->icon('heroicon-o-shield-exclamation'),

            Stat::make('Items in Maintenance', (clone $query)->where('status', 'in maintenance')->count())
                ->description('Currently under maintenance')
                ->color('warning')
                ->icon('heroicon-o-cog'),
        ];
    }
}
