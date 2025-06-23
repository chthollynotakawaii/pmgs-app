<?php
namespace App\Filament\Widgets;

use App\Models\UniformDistribution;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;


class UniformDistributionChart extends ChartWidget
{   
    protected static ?string $heading = 'Uniform Distributions';
    protected static ?int $sort = 3;

    protected array $filters = [];


    protected function getData(): array
    {   
        $query = UniformDistribution::query();

        $start = $this->filters['start_date'] ?? null;
        $end = $this->filters['end_date'] ?? null;

        if ($start) {
            $query->where('created_at', '>=', Carbon::parse($start)->startOfDay());
        }

        if ($end) {
            $query->where('created_at', '<=', Carbon::parse($end)->endOfDay());
        }

        $records = $query->get();

        $groupBy = 'd';
        if ($start && $end && Carbon::parse($start)->diffInDays($end) <= 31) {
            $groupBy = 'd';
        } elseif ($start && $end && Carbon::parse($start)->diffInMonths($end) < 12) {
            $groupBy = 'm';
        } else {
            $groupBy = 'y';
        }

        $labels = [];
        $counts = [];

        $grouped = match ($groupBy) {
            'd' => $records->groupBy(fn ($r) => Carbon::parse($r->created_at)->format('Y-m-d')),
            'm' => $records->groupBy(fn ($r) => Carbon::parse($r->created_at)->format('Y-m')),
            'y' => $records->groupBy(fn ($r) => Carbon::parse($r->created_at)->format('Y')),
        };

        foreach ($grouped as $label => $group) {
            $labels[] = $label;
            $counts[] = $group->count();
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Distributions',
                    'data' => $counts,
                    'backgroundColor' => '#4CAF50',
                    'borderColor' => '#388E3C',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
