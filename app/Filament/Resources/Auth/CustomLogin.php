<?php
namespace App\Filament\Resources\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Login;
use Filament\Forms\Form;

class CustomLogin extends Login
{
    public function getHeading(): string
    {
        return 'PMGS'; // Removes the "Sign in" text
    }
    public function form(Form $form): Form
    {   
        return $form->schema([
            TextInput::make('username')
                ->label('Username')
                ->required()
                ->autocomplete('username'),
            TextInput::make('password')
                ->label('Password')
                ->password()
                ->required()
                ->autocomplete('current-password'),
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
        return 'filament.admin.login';
    }

}