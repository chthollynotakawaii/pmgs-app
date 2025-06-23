<?php

namespace App\Filament\Resources\InventoryRecordResource\Pages;

use App\Filament\Resources\InventoryRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListInventoryRecords extends ListRecords
{
    protected static string $resource = InventoryRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                    ->label('Add Inventory Item') // Change button text
                    ->icon('heroicon-o-plus-circle'), // Change icon
        ];
    }
    public function getTabs(): array
    {
        return [
            'All' => Tab::make('All'),
            'Borrowed' => Tab::make('Borrowed')->modifyQueryUsing(fn ($query) => $query->where('borrowed', true)),
            'Available' => Tab::make('Available')->modifyQueryUsing(fn ($query) => $query->where('borrowed', false)),
            'Functional' => Tab::make('Functional')->modifyQueryUsing(fn ($query) => $query->where('status', 'functional')),
            'Defective' => Tab::make('Defective')->modifyQueryUsing(fn ($query) => $query->where('status', 'defective')),
            'Damaged' => Tab::make('Damaged')->modifyQueryUsing(fn ($query) => $query->where('status', 'damaged')),
            'In Maintenance' => Tab::make('In Maintenance')->modifyQueryUsing(fn ($query) => $query->where('status', 'in maintenance')),
            
        ];
    }
}
