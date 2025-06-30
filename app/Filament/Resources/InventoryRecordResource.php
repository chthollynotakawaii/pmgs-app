<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryRecordResource\Pages;
use App\Models\InventoryRecord;
use App\Models\Brand;
use App\Models\Models;
use App\Models\Category;
use App\Models\Department;
use App\Models\Supplier;
use App\Models\Location;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Illuminate\Support\Str;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Actions\ExportAction;
use App\Filament\Exports\InventoryRecordExporter;


class InventoryRecordResource extends Resource
{
    protected static ?string $model = InventoryRecord::class;
    protected static ?string $navigationGroup = 'Inventory Management';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-s-list-bullet';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Inventory Form')
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make('Inventory Details')
                            ->columns(2)
                            ->schema([

                                TextInput::make('serial_number')
                                    ->label('Serial Number')
                                    ->default(fn () => 'MMACI-' . strtoupper(Str::random(10)))
                                    ->required()

                                    ->dehydrated() // ensure it is submitted even if hidden
                                    ->extraInputAttributes(['class' => 'text-center'])
                                    ->columnSpanFull(),

                                TextInput::make('temp_serial')
                                    ->label('Serial Number')
                                    ->required()
                                    ->columnSpanFull(),

                                TextInput::make('qty')
                                    ->label('Quantity')
                                    ->numeric()
                                    ->integer()
                                    ->minValue(1)
                                    ->required()
                                    ->default(1)
                                    ->columnSpan(1),

                                Select::make('unit')
                                    ->label('Unit')
                                    ->required()
                                    ->options([
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
                                    ->searchable()
                                    ->columnSpan(1),

                               Select::make('brand_id')
                                    ->label('Brand')
                                    ->relationship('brand', 'name', fn ($query) => $query->orderBy('name'))
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(1),

                                Select::make('model_id')
                                    ->label('Model')
                                    ->relationship('model', 'name', fn ($query) => $query->orderBy('name'))
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(1),


                                Textarea::make('description')
                                    ->label('Description')
                                    ->columnSpanFull()
                                    ->placeholder('Enter a detailed description of the inventory item.')
                            ]),

                        Tabs\Tab::make('Classification Info')
                            ->columns(2)
                            ->schema([
                                Select::make('category_id')
                                    ->label('Category')
                                    ->relationship('category', 'name', fn ($query) => $query->orderBy('name'))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpan(1),

                                Select::make('department_id')
                                    ->label('Department')
                                    ->relationship('department', 'name', fn ($query) => $query->orderBy('name'))
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(1),

                                Select::make('supplier_id')
                                    ->label('Supplier')
                                    ->relationship('supplier', 'name', fn ($query) => $query->orderBy('name'))
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(1),

                                Select::make('location_id')
                                    ->label('Location')
                                    ->relationship('location', 'name', fn ($query) => $query->orderBy('name'))
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(1),

                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'Functional' => 'Functional',
                                        'Defective' => 'Defective',
                                        'Damaged' => 'Damaged',
                                        'In Maintenance' => 'In Maintenance',
                                    ])
                                    ->columnSpan(1),

                                DateTimePicker::make('recorded_at')
                                    ->label('Recorded At')
                                    ->placeholder('Not Returned')
                                    ->default(now())
                                    ->columnSpan(1),

                                Textarea::make('remarks')
                                    ->label('Remarks')
                                    ->columnSpanFull()
                                    ->placeholder('Enter any additional remarks or notes about the inventory item.'),
                                ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('qty')->label('Quantity')->sortable()->toggleable(),
                TextColumn::make('unit')->label('Unit')->toggleable(),
                TextColumn::make('description')->label('Description')->toggleable()->wrap()->searchable(),
                TextColumn::make('brand.name')->label('Brand')->sortable()->searchable()->toggleable(),
                TextColumn::make('model.name')->label('Model')->sortable()->searchable()->toggleable(),
                TextColumn::make('serial_number')->label('Serial Number')->searchable()->toggleable(),
                TextColumn::make('temp_serial')->label('Serial Number')->searchable()->toggleable(),
                TextColumn::make('status')->label('Status')->sortable()->searchable()->toggleable()
                ->badge()
                ->color(fn ($state) => match ($state) {
                    'Functional' => 'success',
                    'Defective' => 'danger',
                    'Damaged' => 'warning',
                    'In Maintenance' => 'info',
                    default => 'secondary',
                }),
                TextColumn::make('remarks')->label('remarks')->toggleable()->wrap()->searchable(),
                TextColumn::make('category.name')->label('Category')->sortable()->searchable()->toggleable(),
                TextColumn::make('department.name')->label('Department')->sortable()->searchable()->toggleable(),
                TextColumn::make('location.name')->label('Location')->sortable()->searchable()->toggleable(),
                TextColumn::make('supplier.name')->label('Supplier')->sortable()->searchable()->toggleable(),
                ToggleColumn::make('borrowed')
                ->label('Borrowed')
                ->sortable()
                ->toggleable(),
                TextColumn::make('recorded_at')->label('Recorded At')->dateTime()->sortable()->toggleable(),
                TextColumn::make('created_at')->label('Created At')->dateTime()->sortable()->toggleable(),
                TextColumn::make('updated_at')->label('Updated At')->dateTime()->sortable()->toggleable(),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('brand_id')->label('Brand')->relationship('brand', 'name'),
                SelectFilter::make('model_id')->label('Model')->relationship('model', 'name'),
                SelectFilter::make('category_id')->label('Category')->relationship('category', 'name'),
                SelectFilter::make('department_id')->label('Department')->relationship('department', 'name'),
                SelectFilter::make('location_id')->label('Location')->relationship('location', 'name'),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')->label('From'),
                        DatePicker::make('until')->label('Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('generate_qr')
                        ->label('QR Code')
                        ->icon('heroicon-o-qr-code')
                        ->action(fn ($record) =>
                            redirect()->route('inventory.qr.download', ['id' => $record->id])
                        ),
                    Tables\Actions\RestoreAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                ])
            ])
            ->headerActions([
                ExportAction::make()->Exporter(InventoryRecordExporter::class)->label('Export Inventory Records'),
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
            \App\Filament\Resources\InventoryRecordResource\RelationManagers\BorrowingLogsRelationManager::class,
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([
            \Illuminate\Database\Eloquent\SoftDeletingScope::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventoryRecords::route('/'),
            'create' => Pages\CreateInventoryRecord::route('/create'),
            'edit' => Pages\EditInventoryRecord::route('/{record}/edit'),
        ];
    }
}