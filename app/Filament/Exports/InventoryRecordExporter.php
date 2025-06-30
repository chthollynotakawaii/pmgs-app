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
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('qty')->label('Quantity'),
            ExportColumn::make('unit')->label('Unit'),
            ExportColumn::make('description')->label('Description'),
            ExportColumn::make('brand.name')->label('Brand'),
            ExportColumn::make('model.name')->label('Model'),
            ExportColumn::make('temp_serial')->label('Serial Number'),
            ExportColumn::make('remarks')->label('Remarks'),
            ExportColumn::make('status')->label('Status'),
            ExportColumn::make('category.name')->label('Category'),
            ExportColumn::make('department.name')->label('Department'),
            ExportColumn::make('location.name')->label('Location'),
            ExportColumn::make('supplier.name')->label('Supplier'),
            ExportColumn::make('borrowed')->label('Borrowed'),
            ExportColumn::make('recorded_at')->label('Date Recorded'),
            ExportColumn::make('created_at')->label('Created At'),
            ExportColumn::make('updated_at')->label('Updated At'),
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
