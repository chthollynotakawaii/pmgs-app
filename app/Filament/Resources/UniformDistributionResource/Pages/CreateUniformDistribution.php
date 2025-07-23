<?php

namespace App\Filament\Resources\UniformDistributionResource\Pages;

use App\Filament\Resources\UniformDistributionResource;
use App\Models\UniformDistribution;
use Filament\Resources\Pages\CreateRecord;

class CreateUniformDistribution extends CreateRecord
{
    protected static string $resource = UniformDistributionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
