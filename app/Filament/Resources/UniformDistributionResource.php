<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UniformDistributionResource\Pages;
use App\Models\Department;
use App\Models\UniformDistribution;
use App\Models\UniformInventory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Get;

class UniformDistributionResource extends Resource
{
    protected static ?string $model = UniformDistribution::class;
    protected static ?string $navigationGroup = 'Inventory Management';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationIcon = 'heroicon-o-equals';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('uniform_inventory_id')
                ->label('Uniform')
                ->options(
                    UniformInventory::with('inventoryRecord')->get()->mapWithKeys(function ($item) {
                        $serial = $item->inventoryRecord?->serial_number ?? 'N/A';
                        return [$item->id => "{$item->type} - {$item->size} - SN: {$serial} (Stock: {$item->quantity})"];
                    })
                )
                ->required()
                ->searchable(),

            TextInput::make('student_id')
                ->label('Student ID')
                ->numeric()
                ->required(),

            TextInput::make('student_name')
                ->label('Student Name')
                ->required(),

            Select::make('department_id')
                ->label('Department')
                ->options(Department::all()->pluck('name', 'id'))
                ->required()
                ->searchable(),

            TextInput::make('receipt_number')
                ->label('Receipt Number')
                ->numeric()
                ->required(),

            TextInput::make('quantity')
                ->numeric()
                ->required()
                ->minValue(1)
                ->label('Quantity')
                ->rules([
                    fn (Get $get) => function (string $attribute, $value, $fail) use ($get) {
                        $uniformInventoryId = $get('uniform_inventory_id');
                        $available = \App\Models\UniformInventory::find($uniformInventoryId)?->quantity ?? 0;

                        if ($value > $available) {
                            $fail("Not enough stock. Available quantity: {$available}.");
                        }
                    },
                ]),

            Textarea::make('remarks')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('uniformInventory.type')
                    ->label('Type')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('uniformInventory.size')
                    ->label('Size')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('uniformInventory.inventoryRecord.serial_number')
                    ->label('Serial No.')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('student_id')
                    ->label('Student ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('student_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('department.name')
                    ->label('Department')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('receipt_number')
                    ->label('Receipt #')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('quantity')
                    ->label('Qty')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('uniform_inventory_id')
                    ->label('Uniform Type')
                    ->options(UniformInventory::all()->pluck('type', 'id')),

                SelectFilter::make('department_id')
                    ->label('Department')
                    ->options(Department::all()->pluck('name', 'id')),

                Filter::make('created_at')
                    ->label('Date Range')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('From'),
                        Forms\Components\DatePicker::make('created_until')->label('To'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn ($q) => $q->whereDate('created_at', '>=', $data['created_from']))
                            ->when($data['created_until'], fn ($q) => $q->whereDate('created_at', '<=', $data['created_until']));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUniformDistributions::route('/'),
            'create' => Pages\CreateUniformDistribution::route('/create'),
            'edit' => Pages\EditUniformDistribution::route('/{record}/edit'),
        ];
    }
}
