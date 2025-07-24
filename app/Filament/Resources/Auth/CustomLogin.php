<?php

namespace App\Filament\Resources\Auth;

use Filament\Forms\Form;
use Filament\Pages\Auth\Login;
use Filament\Forms\Components\TextInput;
use Filament\Facades\Filament;
use Illuminate\Validation\ValidationException;
// use DiogoGPinto\AuthUIEnhancer\Pages\Auth\Concerns\HasCustomLayout;

class CustomLogin extends Login
{
    // use HasCustomLayout;
    public function getHeading(): string
    {
        return 'PMGS';
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getUsernameFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                    ])
                    ->statePath('data')
            ),
        ];
    }
 protected function getUsernameFormComponent(): TextInput
{
    return TextInput::make('username')
        ->label('Username')
        ->required()
        ->autofocus()
        ->autocomplete()
        ->extraAttributes(['tabindex' => 1])
        ->extraInputAttributes([
            'class' => 'bg-white rounded-md px-3 py-2',
        ]);
}

protected function getPasswordFormComponent(): TextInput
{
    return TextInput::make('password')
        ->label('Password')
        ->password()
        ->required()
        ->autocomplete('current-password')
        ->extraInputAttributes([
            'class' => 'bg-white rounded-md px-3 py-2',
        ]);
}


    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
    }

    public static function getRouteName(): string
    {
        return 'filament.' . Filament::getCurrentPanel()->getId() . '.login';
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.username' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }
}