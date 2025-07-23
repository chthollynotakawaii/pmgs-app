<?php

namespace App\Filament\Widgets;

use App\Models\BorrowingLog;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class OverdueBorrowingWidget extends Widget
{
    protected static string $view = 'filament.widgets.overdue-borrowing-widget';

    public function getData(): array
    {
        $today = Carbon::today();

        return [
            'overdues' => BorrowingLog::with(['inventoryRecord'])
                ->where('remarks', false)
                ->whereDate('returned_at', '<', $today)
                ->get(),
        ];
    }
}
