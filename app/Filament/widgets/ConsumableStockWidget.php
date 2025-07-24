<?php

namespace App\Filament\Widgets;

use App\Models\Consumable;
use Filament\Widgets\Widget;

class ConsumableStockWidget extends Widget
{
    protected static string $view = 'filament.widgets.consumable-stock-widget';

    public $lowStock;

    public function mount(): void
    {
        $this->lowStock = Consumable::where('stock', '<=', 10)->get();
    }
}
