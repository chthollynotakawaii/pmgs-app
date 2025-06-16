<?php

namespace App\Filament\Resources\BorrowingLogResource\Pages;

use App\Filament\Resources\BorrowingLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBorrowingLog extends EditRecord
{
    protected static string $resource = BorrowingLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
        protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
