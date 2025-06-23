<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BorrowingLogResource\Pages;
use App\Filament\Resources\BorrowingLogResource\Pages\EditBorrowingLog;
use App\Models\BorrowingLog;
use App\Models\InventoryRecord;
use App\Models\User;
use App\Models\Location;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ExportAction;
use App\Filament\Exports\BorrowingLogExporter;

class BorrowingLogResource extends Resource
{
    protected static ?string $model = BorrowingLog::class;
    protected static ?string $navigationGroup = 'Inventory Management';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationBadge = null;

    public static function getNavigationBadge(): ?string
    {
        return (string) BorrowingLog::whereNull('returned_at')->count();
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('inventory_record_id')
                ->label('Inventory Item')
                ->searchable()
                ->options(function () {
                    return InventoryRecord::where('borrowed', true)
                        ->where('qty', '>', 0)
                        ->get()
                        ->mapWithKeys(function ($record) {
                            return [$record->id => "{$record->serial_number} (Available: {$record->qty})"];
                        });
                })
                ->getSearchResultsUsing(fn (string $search) =>
                    InventoryRecord::where('borrowed', true)
                        ->where('serial_number', 'like', "%{$search}%")
                        ->where('qty', '>', 0)
                        ->get()
                        ->mapWithKeys(fn ($record) =>
                            [$record->id => "{$record->serial_number} (Available: {$record->qty})"]
                        )
                )
                ->getOptionLabelUsing(fn ($value) =>
                    ($r = InventoryRecord::find($value)) ? "{$r->serial_number} (Available: {$r->qty})" : 'N/A'
                )
                ->columnSpan(fn ($livewire) =>
                    $livewire instanceof EditBorrowingLog ? 2 : 1
                )
                ->required(),

            TextInput::make('quantity')
                ->label('Quantity')
                ->numeric()
                ->integer()
                ->minValue(1)
                ->default(1)
                ->required(),

            Select::make('user_id')
                ->label('Borrower (Property In Charge)')
                ->searchable()
                ->options(
                    User::query()
                        ->latest()
                        ->limit(10)
                        ->pluck('name', 'id')
                )
                ->getSearchResultsUsing(fn (string $search) =>
                    User::query()
                        ->where('name', 'like', "%{$search}%")
                        ->limit(10)
                        ->pluck('name', 'id')
                )
                ->getOptionLabelUsing(fn ($value) =>
                    User::find($value)?->name
                )
                ->reactive()
                ->requiredWithout('custom_borrower')
                ->visible(fn ($get) => blank($get('custom_borrower'))),

            TextInput::make('custom_borrower')
                ->label('Borrower (If Student/Staff Not Listed)')
                ->requiredWithout('user_id')
                ->visible(fn ($get) => blank($get('user_id'))),

            Select::make('location_id')
                ->label('Location')
                ->searchable()
                ->options(
                    Location::query()
                        ->latest()
                        ->limit(10)
                        ->pluck('name', 'id')
                )
                ->getSearchResultsUsing(fn (string $search) =>
                    Location::query()
                        ->where('name', 'like', "%{$search}%")
                        ->limit(10)
                        ->pluck('name', 'id')
                )
                ->getOptionLabelUsing(fn ($value) =>
                    Location::find($value)?->name
                )
                                ->columnSpan(fn ($livewire) =>
                    $livewire instanceof EditBorrowingLog ? 1 : 2
                )
                ->required(),

            DateTimePicker::make('returned_at')
                ->label('Returned At')
                ->placeholder('Not Returned')
                ->required()
                ->visibleOn('edit'),

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

                TextColumn::make('quantity')
                    ->label('Qty')
                    ->sortable(),

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
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('returned_at')
                    ->label('Returned At')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Not Returned'),

                TextColumn::make('remarks')
                    ->label('Remarks')
                    ->wrap(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                ExportAction::make()->Exporter(BorrowingLogExporter::class)->label('Export Borrowing Logs')
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
