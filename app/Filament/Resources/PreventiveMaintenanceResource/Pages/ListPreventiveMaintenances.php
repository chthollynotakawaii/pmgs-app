<?php

namespace App\Filament\Resources\PreventiveMaintenanceResource\Pages;

use App\Filament\Resources\PreventiveMaintenanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPreventiveMaintenances extends ListRecords
{
    protected static string $resource = PreventiveMaintenanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Schedule Maintenance') // Change button text
                ->icon('heroicon-o-plus-circle'), // Change icon
        ];
    }
}
