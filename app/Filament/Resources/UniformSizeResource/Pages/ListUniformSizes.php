<?php

namespace App\Filament\Resources\UniformSizeResource\Pages;

use App\Filament\Resources\UniformSizeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUniformSizes extends ListRecords
{
    protected static string $resource = UniformSizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add Uniform Size') // Change button text
                ->icon('heroicon-o-plus-circle'), // Change icon,
        ];
    }
}
