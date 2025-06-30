<?php

namespace App\Filament\Exports;

use App\Models\UniformSize;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class UniformSizeExporter extends Exporter
{
    protected static ?string $model = UniformSize::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('student_name')->label('Student Name'),
            ExportColumn::make('student_identification')->label('Student ID'),
            ExportColumn::make('department.name')->label('Department'),
            ExportColumn::make('course.name')->label('Course'),
            ExportColumn::make('sizes')
                ->label('Uniform Sizes')
                ->getStateUsing(function ($record) {
                    $details = is_array($record->sizes) ? $record->sizes : json_decode($record->sizes, true);

                    if (!is_array($details)) {
                        return [];
                    }

                    return collect($details)->map(function ($item) {
                        $type = $item['uniform_type'] ?? '-';
                        $size = $item['size'] ?? '-';
                        $qty = $item['quantity'] ?? '-';

                        return "Type: $type | Size: $size | Quantity: $qty";
                    })->values()->all();
                }),
            ExportColumn::make('created_at')->label('Date'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your uniform size export has completed and ' .
            number_format($export->successful_rows) . ' ' .
            str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' .
                str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
