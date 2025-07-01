<?php

namespace App\Filament\Resources\Auth;

use Filament\Forms\Form;
use Filament\Pages\Auth\Login;
use Filament\Forms\Components\TextInput;
use Filament\Facades\Filament;
use Illuminate\Validation\ValidationException;

class CustomLogin extends Login
{
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
            ->extraAttributes(['tabindex' => 1]);
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
