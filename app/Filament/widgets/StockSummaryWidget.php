<?php
namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\UniformInventory;
use App\Models\UniformDistribution;
use App\Models\UniformStockSummary;
use Filament\Widgets\Widget;

class StockSummaryWidget extends Widget
{
    protected static string $view = 'filament.widgets.stock-summary-widget';

    public function getViewData(): array
    {
        $stock = [];

        // Total stock from UniformInventory.details (raw input)
        foreach (UniformInventory::all() as $inv) {
            $details = is_array($inv->details) ? $inv->details : json_decode($inv->details, true);

            foreach ($details as $item) {
                $key = $inv->course_id . '|' . $item['uniform_type'] . '|' . $item['size'];

                if (!isset($stock[$key])) {
                    $stock[$key] = [
                        'course_id' => $inv->course_id,
                        'uniform_type' => $item['uniform_type'],
                        'size' => $item['size'],
                        'total' => 0,
                        'distributed' => 0,
                        'remaining' => 0,
                    ];
                }

                $stock[$key]['total'] += (int) $item['quantity'];
            }
        }

        // Distributed stock from UniformDistribution
        foreach (UniformDistribution::with('uniformSize')->get() as $dist) {
            $uniformSize = $dist->uniformSize;
            if (! $uniformSize || ! is_array($dist->sizes_id)) continue;

            foreach ($dist->sizes_id as $entry) {
                if (! is_array($entry)) continue;

                $key = $uniformSize->course_id . '|' . $entry['uniform_type'] . '|' . $entry['size'];

                if (!isset($stock[$key])) {
                    $stock[$key] = [
                        'course_id' => $uniformSize->course_id,
                        'uniform_type' => $entry['uniform_type'],
                        'size' => $entry['size'],
                        'total' => 0,
                        'distributed' => 0,
                        'remaining' => 0,
                    ];
                }

                $stock[$key]['distributed'] += (int) $entry['quantity'];
            }
        }

        // Remaining stock from UniformStockSummary
        foreach (UniformStockSummary::all() as $summary) {
            $key = $summary->course_id . '|' . $summary->uniform_type . '|' . $summary->size;

            if (!isset($stock[$key])) {
                $stock[$key] = [
                    'course_id' => $summary->course_id,
                    'uniform_type' => $summary->uniform_type,
                    'size' => $summary->size,
                    'total' => 0,
                    'distributed' => 0,
                    'remaining' => 0,
                ];
            }

            $stock[$key]['remaining'] = $summary->total_quantity;
        }

        // Final fallback calculation for remaining = total - distributed
        foreach ($stock as &$row) {
            if (!isset($row['remaining']) || $row['remaining'] === 0) {
                $row['remaining'] = $row['total'] - $row['distributed'];
            }
        }

        // Group by course name
        $grouped = collect($stock)->groupBy(fn ($row) =>
            Course::find($row['course_id'])?->name ?? 'Unknown'
        );

        return compact('grouped');
    }
    public static function pollingInterval(): ?string
    {
        return '5m'; // or '30s', '1m', etc.
    }

}
