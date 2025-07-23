<?php

namespace App\Filament\Resources\ConsumableRequestResource\Pages;

use App\Filament\Resources\ConsumableRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConsumableRequest extends EditRecord
{
    protected static string $resource = ConsumableRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
