<?php

namespace App\Filament\Resources\PropertyRequestResource\Pages;

use App\Filament\Resources\PropertyRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPropertyRequest extends EditRecord
{
    protected static string $resource = PropertyRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
