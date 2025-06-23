<?php

namespace App\Filament\Widgets;

use App\Models\InventoryRecord;
use Filament\Widgets\TableWidget;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

class InventoryListWidget extends TableWidget
{
    protected static ?string $heading = 'Today\'s Recent Inventory';
    protected static ?int $sort = 2; // Adjust the sort order as needed

    protected function getTableQuery(): Builder
    {
        return InventoryRecord::query()
            ->whereDate('created_at', Carbon::today())
            ->latest('created_at');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('serial_number')->label('Serial Number')->sortable()->searchable(),
            TextColumn::make('qty')->label('Quantity'),
            TextColumn::make('unit')->label('Unit'),
            TextColumn::make('brand.name')->label('Brand'),
            TextColumn::make('model.name')->label('Model'),
        ];
    }
}
