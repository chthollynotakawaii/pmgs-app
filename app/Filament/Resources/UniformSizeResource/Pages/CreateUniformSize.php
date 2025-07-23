<?php

namespace App\Filament\Resources\UniformSizeResource\Pages;

use App\Filament\Resources\UniformSizeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUniformSize extends CreateRecord
{
    protected static string $resource = UniformSizeResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
