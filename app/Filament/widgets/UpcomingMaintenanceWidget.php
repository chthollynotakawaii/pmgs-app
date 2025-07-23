<?php

namespace App\Filament\Widgets;

use App\Models\PreventiveMaintenance;
use App\Models\InventoryRecord;
use Filament\Widgets\Widget;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class UpcomingMaintenanceWidget extends Widget
{
    protected static string $view = 'filament.widgets.upcoming-maintenance-widget';

    protected function getTableQuery(): Builder
    {
        $query = InventoryRecord::query()
            ->whereDate('created_at', Carbon::today())
            ->latest('created_at');

        if (Auth::user()?->role !== 'admin') {
            $query->where('department_id', Auth::user()->department_id);
        }

        return $query;
    }
    public function getColumnSpan(): int|string
    {
        return 'full';
    }

    public function getData(): array
    {
        $user = Auth::user();
        $oneMonthAhead = Carbon::now()->addMonth();

        $query = PreventiveMaintenance::whereBetween('scheduled_date', [now(), $oneMonthAhead]);

        if ($user?->role !== 'admin') {
            $query = $query->get()->filter(function ($maintenance) use ($user) {
                $ids = is_array($maintenance->inventory_record_ids)
                    ? $maintenance->inventory_record_ids
                    : json_decode($maintenance->inventory_record_ids, true);

                return InventoryRecord::whereIn('id', $ids)
                    ->where('department_id', $user->department_id)
                    ->exists();
            });

            return ['maintenances' => $query->values()];
        }

        return ['maintenances' => $query->get()];
    }


    public static function pollingInterval(): ?string
    {
        return '5m';
    }
}
