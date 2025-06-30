<?php

namespace App\Filament\Resources\UniformInventoryResource\Pages;

use App\Filament\Resources\UniformInventoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUniformInventory extends EditRecord
{
    protected static string $resource = UniformInventoryResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
