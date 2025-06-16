<?php

namespace App\Filament\Resources\BorrowingLogResource\Pages;

use App\Filament\Resources\BorrowingLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBorrowingLogs extends ListRecords
{
    protected static string $resource = BorrowingLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Borrow Item') // Change button text
                ->icon('heroicon-o-plus-circle'), // Change icon
        ];
    }
    
}
