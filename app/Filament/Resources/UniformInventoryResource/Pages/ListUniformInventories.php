<?php

namespace App\Filament\Resources\UniformInventoryResource\Pages;

use App\Filament\Resources\UniformInventoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUniformInventory extends ListRecords
{
    protected static string $resource = UniformInventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add Uniform Inventory') // Change button text
                ->icon('heroicon-o-plus-circle'), // Change icon
        ];
    }
}
