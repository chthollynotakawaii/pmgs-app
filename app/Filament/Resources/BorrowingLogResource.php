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
                    ->searchable()
                    ->getSearchResultsUsing(fn (string $search) => 
                        \App\Models\InventoryRecord::query()
                            ->where('borrowed', true)
                            ->where('serial_number', 'like', "%{$search}%")
                            ->limit(10)
                            ->pluck('serial_number', 'id')
                    )
                    ->getOptionLabelUsing(fn ($value) => 
                        \App\Models\InventoryRecord::find($value)?->serial_number
                    )
                    ->required()
                    ->columnSpanFull(),


                Select::make('user_id')
                    ->label('Borrower')
                    ->searchable()
                    ->getSearchResultsUsing(fn (string $search) => 
                        \App\Models\User::query()
                            ->where('name', 'like', "%{$search}%")
                            ->limit(10)
                            ->pluck('name', 'id')
                    )
                    ->getOptionLabelUsing(fn ($value) => 
                        \App\Models\User::find($value)?->name
                    )
                    ->required()
                    ->columnSpanFull(),

                Select::make('location_id')
                    ->label('Location')
                    ->searchable()
                    ->getSearchResultsUsing(fn (string $search) => 
                        \App\Models\Location::query()
                            ->where('name', 'like', "%{$search}%")
                            ->limit(10)
                            ->pluck('name', 'id')
                    )
                    ->getOptionLabelUsing(fn ($value) => 
                        \App\Models\Location::find($value)?->name
                    )
                    ->required()
                    ->columnSpanFull(),
                    
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
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('user.name')
                    ->label('Borrower')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('location.name')
                    ->label('Location')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Borrowed At')
                    ->date()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('returned_at')
                    ->label('Returned At')
                    ->date()
                    ->sortable()
                    ->placeholder('Not Returned')
                    ->toggleable(),

                TextColumn::make('remarks')
                    ->label('Remarks')
                    ->wrap()
                    ->toggleable()
                    ->limit(50),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
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
