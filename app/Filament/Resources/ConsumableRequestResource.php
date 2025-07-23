<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsumableRequestResource\Pages;
use App\Filament\Resources\ConsumableRequestResource\RelationManagers;
use App\Models\ConsumableRequest;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConsumableRequestResource extends Resource
{
    protected static ?string $model = ConsumableRequest::class;
    protected static ?string $navigationGroup = 'Property Management';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Select::make('consumable_id')
                    ->relationship('consumable', 'name')
                    ->required(),

                Select::make('requested_by')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                TextInput::make('quantity')->numeric()->required(),

                RichEditor::make('purpose'),
                DatePicker::make('requested_at')->default(now()),

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
            'index' => Pages\ListConsumableRequests::route('/'),
            'create' => Pages\CreateConsumableRequest::route('/create'),
            'edit' => Pages\EditConsumableRequest::route('/{record}/edit'),
        ];
    }
}
