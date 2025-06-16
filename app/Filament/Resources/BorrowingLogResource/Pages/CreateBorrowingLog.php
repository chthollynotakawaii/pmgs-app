<?php

namespace App\Filament\Resources\BorrowingLogResource\Pages;

use App\Filament\Resources\BorrowingLogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBorrowingLog extends CreateRecord
{
    protected static string $resource = BorrowingLogResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
