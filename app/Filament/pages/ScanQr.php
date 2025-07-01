<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ScanQr extends Page
{
    protected static ?string $navigationIcon = 'heroicon-s-qr-code';

    protected static string $view = 'filament.pages.scan-qr';
}
