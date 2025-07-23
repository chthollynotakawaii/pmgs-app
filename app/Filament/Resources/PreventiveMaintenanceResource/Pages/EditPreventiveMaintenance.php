<?php

namespace App\Filament\Resources\PreventiveMaintenanceResource\Pages;

use App\Filament\Resources\PreventiveMaintenanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPreventiveMaintenance extends EditRecord
{
    protected static string $resource = PreventiveMaintenanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
}
