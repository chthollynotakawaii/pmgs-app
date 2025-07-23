<?php

namespace App\Filament\Widgets;

use App\Models\InventoryRecord;
use Filament\Widgets\TableWidget;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class InventoryListWidget extends TableWidget
{
    protected static ?string $heading = 'Today\'s Recent Inventory';
    protected static ?int $sort = 3;
    public function getColumnSpan(): int|string
    {
        return 'full';
    }

    protected function getTableQuery(): Builder
    {
        $query = InventoryRecord::query()
            ->whereDate('created_at', Carbon::today())
            ->latest('created_at');

        // If not admin, filter by department
        if (Auth::user()?->role !== 'admin') {
            $query->where('department_id', Auth::user()->department_id);
        }

        return $query;
    }


    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('temp_serial')->label('Serial Number')->sortable()->searchable(),
            TextColumn::make('qty')->label('Quantity'),
            TextColumn::make('unit')->label('Unit'),
            TextColumn::make('brand.name')->label('Brand')->searchable(),
            TextColumn::make('model.name')->label('Model')->searchable(),
        ];
    }
    public static function pollingInterval(): ?string
    {
        return '5m'; // or '30s', '1m', etc.
    }
}
