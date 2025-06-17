<?php

namespace App\Filament\Resources\AdminResource\Pages\Auth;

use App\Filament\Resources\AdminResource;
use Filament\Resources\Pages\Page;

class Login extends Page
{
    protected static string $resource = AdminResource::class;

    protected static string $view = 'filament.resources.admin-resource.pages.auth.login';
}
