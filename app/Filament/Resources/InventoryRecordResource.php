<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryRecordResource\Pages;
use App\Models\InventoryRecord;
use App\Models\Department;
use Filament\Forms\Form;
use Filament\Forms\Components\{TextInput, Textarea, FileUpload, Select, DateTimePicker, Section};
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class InventoryRecordResource extends Resource
{
    protected static ?string $model = InventoryRecord::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Inventory Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('control_number')
                            ->label('Control Number')
                            ->required()
                            ->columnSpanFull()
                            ->default(fn () => 'MMACI-' . strtoupper(Str::random(10)))
                            ->afterStateHydrated(function ($state, callable $set) {
                                if (blank($state)) {
                                    $set('control_number', 'MMACI-' . strtoupper(Str::random(10)));
                                }
                            })
                            ->disabled()
                            ->dehydrated()
                            ->extraInputAttributes(['class' => 'text-center']),

                        TextInput::make('temp_serial')
                            ->label('Serial Number')
                            ->datalist(['N/A'])
                            ->columnSpanFull(),

                        TextInput::make('qty')
                            ->label('Quantity')
                            ->numeric()
                            ->integer()
                            ->minValue(1)
                            ->required()
                            ->default(1),

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
                                'pair' => 'Pairs',
                                'bag' => 'Bag',
                                'can' => 'Can',
                                'jar' => 'Jar',
                                'tube' => 'Tube',
                                'roll' => 'Roll',
                                'strip' => 'Strip',
                            ])
                            ->searchable(),

                        Select::make('brand_id')
                            ->label('Brand')
                            ->relationship('brand', 'name')
                            ->searchable()
                            ->preload(),

                        Select::make('model_id')
                            ->label('Model')
                            ->relationship('model', 'name')
                            ->searchable()
                            ->preload(),

                        Textarea::make('description')
                            ->label('Description')
                            ->placeholder('Enter a detailed description of the inventory item.'),

                        FileUpload::make('thumbnail')
                            ->label('Thumbnail')
                            ->image()
                            ->disk('public')
                            ->directory('thumbnail')
                            ->maxFiles(1)
                            ->visibility('public')
                            ->dehydrated(),
                    ]),

                Section::make('Classification Details')
                    ->columns(2)
                    ->schema([
                        Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('department_id')
                            ->label('Department')
                            ->options(Department::pluck('name', 'id'))
                            ->default(fn () => Auth::user()->department_id)
                            ->disabled(fn () => Auth::user()->role !== 'admin')
                            ->dehydrated()
                            ->required(),

                        Select::make('supplier_id')
                            ->label('Supplier')
                            ->relationship('supplier', 'name')
                            ->searchable()
                            ->preload(),

                        Select::make('location_id')
                            ->label('Location')
                            ->relationship('location', 'name')
                            ->searchable()
                            ->preload(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'Functional' => 'Functional',
                                'Defective' => 'Defective',
                                'Damaged' => 'Damaged',
                                'In Maintenance' => 'In Maintenance',
                                'Lost' => 'Lost',
                                'Disposed' => 'Disposed',
                            ])
                            ->default('Functional'),

                        DateTimePicker::make('recorded_at')
                            ->label('Recorded At')
                            ->default(now()),

                        Textarea::make('remarks')
                            ->label('Remarks')
                            ->placeholder('Enter any additional remarks or notes about the inventory item.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('control_number')->sortable()->searchable(),
                TextColumn::make('description')->limit(40)->wrap(),
                TextColumn::make('qty')->label('Qty'),
                TextColumn::make('unit'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Functional' => 'success',
                        'Defective' => 'danger',
                        'Damaged' => 'warning',
                        'In Maintenance' => 'info',
                        'Lost' => 'gray',
                        'Disposed' => 'gray',
                        default => 'secondary',
                    }),
                TextColumn::make('department.name')->label('Department'),
                TextColumn::make('recorded_at')->dateTime('M d, Y'),
            ])
            ->filters([
                Trashedfilter::make(),
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
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
