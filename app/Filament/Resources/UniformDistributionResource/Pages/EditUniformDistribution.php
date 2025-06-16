<?php

namespace App\Filament\Resources\UniformDistributionResource\Pages;

use App\Filament\Resources\UniformDistributionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUniformDistribution extends EditRecord
{
    protected static string $resource = UniformDistributionResource::class;
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
