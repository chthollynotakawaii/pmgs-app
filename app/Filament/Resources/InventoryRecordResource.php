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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\SelectColumn;

class InventoryRecordResource extends Resource
{
    protected static ?string $model = InventoryRecord::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';


    public static function form(Form $form): Form
    {
    function generateUniqueControlNumber(): string
    {
        do {
            $value = 'MMACI-' . strtoupper(Str::random(10));
        } while (InventoryRecord::where('control_number', $value)->exists());

        return $value;
    }
        return $form
            ->schema([
                Section::make('Inventory Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('control_number')
                            ->label('Control Number')
                            ->required()
                            ->columnSpanFull()
                            ->default(fn () => generateUniqueControlNumber())
                            ->afterStateHydrated(function ($state, callable $set) {
                                if (blank($state)) {
                                    $set('control_number', generateUniqueControlNumber());
                                }
                            })
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $state) {
                                if (InventoryRecord::where('control_number', $state)->exists()) {
                                    $set('control_number', generateUniqueControlNumber());
                                }
                            })
                            ->disabled()
                            ->dehydrated()
                            ->extraInputAttributes(['class' => 'text-center']),

                        TextInput::make('temp_serial')
                            ->label('Serial Number')
                            ->datalist(['N/A'])
                            ->required()
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
                                'unit' => 'Unit',
                                'length' => 'Length',
                            ])
                            ->searchable(),

                        Select::make('brand_id')
                            ->label('Brand')
                            ->relationship('brand', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('model_id')
                            ->label('Model')
                            ->relationship('model', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('description')
                            ->label('Description')
                            ->placeholder('Enter a detailed description of the inventory item.')
                            ->live(onBlur: true) // important: ensures update on edit
                            ->afterStateHydrated(function ($component, $state) {
                                $component->state(strtoupper($state)); // uppercase when loading edit form
                            })
                            ->afterStateUpdated(function (\Filament\Forms\Set $set, $state) {
                                $set('name', strtoupper($state)); // uppercase on typing
                            })
                            ->extraAttributes(['style' => 'text-transform: uppercase;'])
                            ->required(),

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
                            ->relationship('department', 'name')
                            ->searchable()
                            ->preload()
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
                            ->preload()
                            ->required(),

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
                            ->required()
                            ->default('Functional'),

                        DateTimePicker::make('recorded_at')
                            ->label('Recorded At')
                            ->default(now())
                            ->required(),

                        Textarea::make('remarks')
                            ->label('Remarks')
                            ->placeholder('Enter any additional remarks or notes about the inventory item.')
                                ->live(onBlur: true) // important: ensures update on edit
                            ->afterStateHydrated(function ($component, $state) {
                                $component->state(strtoupper($state)); // uppercase when loading edit form
                            })
                            ->afterStateUpdated(function (\Filament\Forms\Set $set, $state) {
                                $set('name', strtoupper($state)); // uppercase on typing
                            })
                            ->extraAttributes(['style' => 'text-transform: uppercase;'])
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginationPageOptions([10, 25, 50, 100])
            ->defaultGroup('status')
            ->striped()
            ->columns([
                TextColumn::make('id')->sortable(),
                ImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->disk('public')
                    ->square()
                    ->size(40)
                    ->toggleable(),

                TextColumn::make('control_number')->searchable()->toggleable(),
                TextColumn::make('qty')->label('Qty')->sortable()->searchable()->toggleable(),
                TextColumn::make('unit')->label('Unit')->sortable()->searchable()->toggleable(),
                TextColumn::make('description')->label('Description')->limit(50)->wrap()->searchable()->toggleable(),
                TextColumn::make('brand.name')->label('Brand')->searchable()->toggleable(),
                TextColumn::make('model.name')->label('Model')->searchable()->toggleable(),
                TextColumn::make('temp_serial')->label('Serial')->searchable()->toggleable(),
                TextColumn::make('category.name')->label('Category')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('department.name')->label('Department')->sortable()->searchable()->toggleable(),
                TextColumn::make('supplier.name')->label('Supplier')->sortable()->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('location.name')->label('Location')->sortable()->searchable()->toggleable(),
                SelectColumn::make('status')->label('Status')->searchable()->sortable()
                    ->options([
                        'Functional' => 'Functional',
                        'Defective' => 'Defective',
                        'Damaged' => 'Damaged',
                        'In Maintenance' => 'In Maintenance',
                        'Lost' => 'Lost',
                        'Disposed' => 'Disposed',
                    ])
                    ->toggleable(),
                TextColumn::make('remarks')->label('Remarks')->limit(50)->wrap()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('recorded_at')->label('Recorded At')->date()->sortable()->toggleable()->dateTimeTooltip(),
                TextColumn::make('created_at')->label('Created At')->date()->sortable()->toggleable(isToggledHiddenByDefault: true)->dateTimeTooltip(),
                TextColumn::make('updated_at')->label('Update At')->date()->sortable()->toggleable(isToggledHiddenByDefault: true)->dateTimeTooltip(),
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
                    Tables\Actions\DeleteAction::make()
                        ->visible(fn ($record) => $record->status === 'Disposed')
                        ->tooltip('Only disposed items can be deleted'),
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
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->searchable();
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
