<?php

namespace App\Filament\Resources\PropertyRequestResource\Pages;

use App\Filament\Resources\PropertyRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPropertyRequests extends ListRecords
{
    protected static string $resource = PropertyRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
