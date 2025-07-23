<?php

namespace App\Filament\Exports;

use App\Models\BorrowingLog;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class BorrowingLogExporter extends Exporter
{
    protected static ?string $model = BorrowingLog::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),

            ExportColumn::make('inventoryRecord.temp_serial')->label('Serial Number'),
            ExportColumn::make('inventoryRecord.unit')->label('Unit'),
            ExportColumn::make('inventoryRecord.description')->label('Description'),
            ExportColumn::make('inventoryRecord.brand.name')->label('Brand'),
            ExportColumn::make('inventoryRecord.model.name')->label('Model'),
            ExportColumn::make('inventoryRecord.category.name')->label('Category'),
            ExportColumn::make('inventoryRecord.department.name')->label('Department From'),
            ExportColumn::make('inventoryRecord.location.name')->label('Location From'),
            ExportColumn::make('user.name')->label('Borrower'),
            ExportColumn::make('user.department.name')->label("Borrower's Department"),
            ExportColumn::make('custom_borrower')->label('External Borrower'),
            ExportColumn::make('location.name')->label('Location To'),
            ExportColumn::make('quantity')->label('Quantity'),
            ExportColumn::make('returned_at')->label('Returned At'),
            ExportColumn::make('created_at')->label('Borrowed At'),
            ExportColumn::make('updated_at')->label('Updated At'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your borrowing log export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
