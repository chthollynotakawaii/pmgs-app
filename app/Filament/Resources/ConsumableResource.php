<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsumableResource\Pages;
use App\Filament\Resources\ConsumableResource\RelationManagers;
use App\Models\Consumable;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConsumableResource extends Resource
{
    protected static ?string $model = Consumable::class;
    protected static ?string $navigationGroup = 'Property Management';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('unit')
                    ->label('Unit')
                    ->required()
                    ->options([
                        'pc' => 'Piece',
                        'pcs' => 'Pieces',
                        'kg' => 'Kilograms',
                        'lb' => 'Pounds',
                        'm' => 'Meters',
                        'ft' => 'Feet',
                        'in' => 'Inches',
                        'cm' => 'Centimeters',
                        'mm' => 'Millimeters',
                        'yd' => 'Yards',
                        'mi' => 'Miles',
                        'gal' => 'Gallons',
                        'L' => 'Liters',
                        'ml' => 'Milliliters',
                        'oz' => 'Ounces',
                        'ea' => 'Each',
                        'set' => 'Set',
                        'box' => 'Box',
                        'pack' => 'Pack',
                        'bag' => 'Bag',
                        'can' => 'Can',
                        'jar' => 'Jar',
                        'tube' => 'Tube',
                        'roll' => 'Roll',
                        'strip' => 'Strip',
                    ])
                    ->searchable(),

                TextInput::make('stock')
                    ->required()
                    ->integer()
                    ->default('1')
                    ->label('Quantity'),

                Textarea::make('name')
                    ->required()
                    ->columnSpanFull()
                    ->label('Desicription'),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('stock')->label('Quantity')->searchable()
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
            'index' => Pages\ListConsumables::route('/'),
            'create' => Pages\CreateConsumable::route('/create'),
            'edit' => Pages\EditConsumable::route('/{record}/edit'),
        ];
    }
}
