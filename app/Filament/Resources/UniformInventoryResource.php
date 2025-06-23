<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UniformInventoryResource\Pages;
use App\Models\UniformInventory;
use App\Models\InventoryRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class UniformInventoryResource extends Resource
{
    protected static ?string $model = UniformInventory::class;
    protected static ?string $label = 'Uniform Stocks';
    protected static ?string $pluralLabel = 'Uniform Stocks';
    protected static ?string $navigationGroup = 'Inventory Management';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    public static function getNavigationBadge(): ?string
    {
        $count = UniformInventory::count();
        $totalQty = UniformInventory::sum('quantity');
        return "{$count} | {$totalQty}";
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('inventory_record_id')
                ->label('Inventory Item')
                ->searchable()
                ->options(
                    InventoryRecord::query()
                        ->whereHas('category', fn ($query) =>
                            $query->where('name', 'UNIFORM')
                        )
                        ->latest()
                        ->limit(10)
                        ->pluck('serial_number', 'id')
                )
                ->getSearchResultsUsing(function (string $search) {
                    return InventoryRecord::whereHas('category', fn ($query) =>
                            $query->where('name', 'UNIFORM')
                        )
                        ->where('serial_number', 'like', "%{$search}%")
                        ->limit(50)
                        ->pluck('serial_number', 'id');
                })
                ->getOptionLabelUsing(function ($value) {
                    return InventoryRecord::find($value)?->serial_number ?? 'N/A';
                })
                ->required(),

            Select::make('type')
                ->label('Uniform Type')
                ->required()
                ->options([
                    'TYPE A UPPER' => 'TYPE A UPPER',
                    'TYPE A LOWER' => 'TYPE A LOWER',
                    'TYPE B UPPER' => 'TYPE B UPPER',
                    'TYPE B LOWER' => 'TYPE B LOWER',
                    'P.E UPPER' => 'P.E UPPER',
                    'P.E LOWER' => 'P.E LOWER'
                ])
                ->searchable(),

            Select::make('size')
                ->label('Size')
                ->options([
                    'XS' => 'XS',
                    'S' => 'S',
                    'M' => 'M',
                    'L' => 'L',
                    'XL' => 'XL',
                    'XXL' => 'XXL'
                ])
                ->searchable()
                ->required(),

            TextInput::make('quantity')
                ->label('Quantity')
                ->numeric()
                ->required()
                ->minValue(1)
                ->default(1)
                ->rule(function (Get $get) {
                    return function (string $attribute, $value, \Closure $fail) use ($get) {
                        $inventoryRecordId = $get('inventory_record_id');
                        $currentRecordId = $get('id');

                        if (!$inventoryRecordId) {
                            return;
                        }

                        $inventoryRecord = InventoryRecord::find($inventoryRecordId);
                        if (!$inventoryRecord) {
                            return;
                        }

                        $assignedQtyQuery = UniformInventory::where('inventory_record_id', $inventoryRecordId);

                        if ($currentRecordId) {
                            $assignedQtyQuery->where('id', '!=', $currentRecordId);
                        }

                        $assignedQty = $assignedQtyQuery->sum('quantity');
                        $availableQty = $inventoryRecord->qty - $assignedQty;

                        if ($value > $availableQty) {
                            $fail("Only $availableQty uniforms are available to assign for this inventory item.");
                        }
                    };
                }),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('inventoryRecord.serial_number')
                ->label('Item')
                ->searchable()
                ->sortable(),

            TextColumn::make('type')
                ->sortable()
                ->searchable()
                ->toggleable(),

            TextColumn::make('size')
                ->sortable()
                ->searchable()
                ->toggleable(),

            TextColumn::make('quantity')
                ->label('In Stock')
                ->sortable()
                ->toggleable(),

            TextColumn::make('created_at')
                ->label('Added At')
                ->date()
                ->sortable()
                ->toggleable(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\ViewAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUniformInventory::route('/'),
            'create' => Pages\CreateUniformInventory::route('/create'),
            'edit' => Pages\EditUniformInventory::route('/{record}/edit'),
        ];
    }
}
