<?php

namespace App\Filament\Exports;

use App\Models\InventoryRecord;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class InventoryRecordExporter extends Exporter
{
    protected static ?string $model = InventoryRecord::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('qty'),
            ExportColumn::make('unit'),
            ExportColumn::make('description'),
            ExportColumn::make('brand_id')->label('Brand'),
            ExportColumn::make('model_id')->label('Model'),
            ExportColumn::make('serial_number'),
            ExportColumn::make('remarks'),
            ExportColumn::make('status'),
            ExportColumn::make('department_id')->label('Department'),
            ExportColumn::make('location_id')->label('Location'),
            ExportColumn::make('supplier_id')->label('Supplier'),
            ExportColumn::make('created_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your inventory record export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
