<?php

namespace App\Filament\Resources\UniformDistributionResource\Pages;

use App\Filament\Resources\UniformDistributionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUniformDistributions extends ListRecords
{
    protected static string $resource = UniformDistributionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add Distribution Record') // Change button text
                ->icon('heroicon-o-plus-circle'), // Change icon
        ];
    }
}
