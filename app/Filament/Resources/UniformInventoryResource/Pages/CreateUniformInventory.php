<?php

namespace App\Filament\Resources\UniformInventoryResource\Pages;

use App\Filament\Resources\UniformInventoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUniformInventory extends CreateRecord
{
    protected static string $resource = UniformInventoryResource::class;
        protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
