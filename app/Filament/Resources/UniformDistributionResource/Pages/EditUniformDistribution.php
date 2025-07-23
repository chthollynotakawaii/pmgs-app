<?php

namespace App\Filament\Resources\UniformDistributionResource\Pages;

use App\Filament\Resources\UniformDistributionResource;
use Filament\Resources\Pages\EditRecord;

class EditUniformDistribution extends EditRecord
{
    protected static string $resource = UniformDistributionResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
