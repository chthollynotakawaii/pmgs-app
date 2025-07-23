<?php

namespace App\Filament\Resources\PreventiveMaintenanceResource\Pages;

use App\Filament\Resources\PreventiveMaintenanceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePreventiveMaintenance extends CreateRecord
{
    protected static string $resource = PreventiveMaintenanceResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
