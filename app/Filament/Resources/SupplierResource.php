<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Models\Supplier;;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Set;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;
    protected static ?string $navigationGroup = 'Input Modifiers';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-s-building-office-2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Supplier')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                TextInput::make('phone')
                    ->label('Phone')
                    ->tel()
                    ->required()
                    ->maxLength(20)
                    ->helperText('Format: +63 912-345-6789')
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        $cleaned = preg_replace('/\D+/', '', $state); // Remove non-digits

                        if (strlen($cleaned) >= 10) {
                            $formatted = '+63 ' . substr($cleaned, -10, 3) . '-' . substr($cleaned, -7, 3) . '-' . substr($cleaned, -4);
                            $set('phone', $formatted);
                        }
                    })
                    ->dehydrateStateUsing(fn (?string $state) => $state),


                
                TextInput::make('address')
                    ->label('Address')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Supplier')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable(),

                TextColumn::make('address')
                    ->label('Address')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
