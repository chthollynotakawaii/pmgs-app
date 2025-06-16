<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BorrowingLogResource\Pages;
use App\Models\BorrowingLog;
use App\Models\InventoryRecord;
use App\Models\User;
use App\Models\Location;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class BorrowingLogResource extends Resource
{
    protected static ?string $model = BorrowingLog::class;
    protected static ?string $navigationGroup = 'Inventory Management';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('inventory_record_id')
                    ->label('Inventory Item')
                    ->options(fn () => InventoryRecord::pluck('serial_number', 'id'))
                    ->searchable()
                    ->columnSpanFull()
                    ->required(),

                Select::make('user_id')
                    ->label('Borrower')
                    ->options(fn () => User::pluck('name', 'id'))
                    ->searchable()
                    ->columnSpanFull()
                    ->required(),

                Select::make('location_id')
                    ->label('Location')
                    ->options(fn () => Location::pluck('name', 'id'))
                    ->searchable()
                    ->columnSpanFull()
                    ->required(),

                Textarea::make('remarks')
                    ->label('Remarks')
                    ->columnSpanFull()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('inventoryRecord.serial_number')
                    ->label('Item')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('Borrower')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('location.name')
                    ->label('Location')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Borrowed At')
                    ->date()
                    ->sortable(),

                TextColumn::make('returned_at')
                    ->label('Returned At')
                    ->date()
                    ->sortable()
                    ->placeholder('Not Returned'),

                TextColumn::make('remarks')
                    ->label('Remarks')
                    ->wrap()
                    ->limit(50),
            ])
            ->filters([])
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBorrowingLogs::route('/'),
            'create' => Pages\CreateBorrowingLog::route('/create'),
            'edit' => Pages\EditBorrowingLog::route('/{record}/edit'),
        ];
    }
}
