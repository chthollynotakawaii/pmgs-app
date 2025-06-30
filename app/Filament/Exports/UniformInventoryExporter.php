<?php
namespace App\Filament\Exports;

use App\Models\UniformInventory;
use App\Models\Course;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class UniformInventoryExporter extends Exporter
{
    protected static ?string $model = UniformInventory::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),

            ExportColumn::make('inventoryRecord.temp_serial')
                ->label('Uniform Serial Number'),

            ExportColumn::make('details')
                ->label('Uniform Details')
                ->state(function (UniformInventory $record) {
                    $details = is_array($record->details)
                        ? $record->details
                        : json_decode($record->details, true);

                    if (!is_array($details)) {
                        return ' ';
                    }

                    $courses = Course::pluck('name', 'id');

                    return collect($details)->map(function ($item) use ($courses) {
                        $type = $item['uniform_type'] ?? $item['Unifom Type'] ?? '-';
                        $size = $item['Size'] ?? '-';
                        $course = $courses[$item['course_id']] ?? '-';
                        $qty = $item['quantity'] ?? '-';
                        return "• Type: $type | Size: $size | Course: $course | Qty: $qty";
                    })->implode("\r\n");
                }),

            ExportColumn::make('created_at')
                ->label('Added At'),

            ExportColumn::make('updated_at')
                ->label('Updated At'),

        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your uniform inventory export has completed and ' .
                number_format($export->successful_rows) . ' ' .
                str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' .
                     str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
