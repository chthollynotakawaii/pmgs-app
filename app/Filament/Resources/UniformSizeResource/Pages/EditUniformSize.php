<?php

namespace App\Filament\Resources\UniformSizeResource\Pages;

use App\Filament\Resources\UniformSizeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUniformSize extends EditRecord
{
    protected static string $resource = UniformSizeResource::class;
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
