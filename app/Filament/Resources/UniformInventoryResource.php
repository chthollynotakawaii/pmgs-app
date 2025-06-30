<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UniformInventoryResource\Pages;
use App\Models\UniformInventory;
use App\Models\InventoryRecord;
use App\Models\Course;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\ExportAction;
use App\Filament\Exports\UniformInventoryExporter;

class UniformInventoryResource extends Resource
{
    protected static ?string $model = UniformInventory::class;
    protected static ?string $label = 'Uniform Stocks';
    protected static ?string $pluralLabel = 'Uniform Stocks';
    protected static ?string $navigationGroup = 'Inventory Management';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-s-clipboard';
    

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('inventory_record_id')
                ->label('Inventory Item')
                ->relationship(
                    name: 'inventoryRecord',
                    titleAttribute: 'temp_serial',
                    modifyQueryUsing: function ($query) {
                        $usedIds = UniformInventory::pluck('inventory_record_id');
                        return $query
                            ->whereHas('category', fn ($q) => $q->where('name', 'UNIFORM'))
                            ->whereNotIn('id', $usedIds);
                    }
                )
                ->searchable()
                ->preload()
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->temp_serial} (Available: {$record->qty})")
                ->columnSpanFull()
                ->required(),

            Repeater::make('details')
                ->label('Uniform Details')
                ->schema([
                    Select::make('uniform_type')
                        ->label('Uniform Type')
                        ->options([
                            'TYPE A UPPER' => 'TYPE A UPPER',
                            'TYPE A LOWER' => 'TYPE A LOWER',
                            'TYPE B UPPER' => 'TYPE B UPPER',
                            'TYPE B LOWER' => 'TYPE B LOWER',
                            'TYPE P.E UPPER' => 'TYPE P.E UPPER',
                            'TYPE P.E LOWER' => 'TYPE P.E LOWER',
                        ])
                        ->searchable()
                        ->required(),

                    Select::make('size')
                        ->label('Size')
                        ->options([
                            'XS' => 'XS',
                            'S' => 'S',
                            'M' => 'M',
                            'L' => 'L',
                            'XL' => 'XL',
                            'XXL' => 'XXL',
                        ])
                        ->searchable()
                        ->required(),

                    Select::make('course_id')
                        ->label('Course')
                        ->options(Course::all()->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->required(),

                    TextInput::make('quantity')
                        ->label('Quantity')
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                ])
                ->rule(function (callable $get) {
                    return function (string $attribute, $value, \Closure $fail) use ($get) {
                        $details = $value ?? [];
                        $inventoryRecordId = $get('inventory_record_id');

                        if (!$inventoryRecordId) {
                            return;
                        }

                        $total = collect($details)->sum(fn ($item) => isset($item['quantity']) ? (int) $item['quantity'] : 0);
                        $inventoryRecord = InventoryRecord::find($inventoryRecordId);

                        if ($inventoryRecord && $total > $inventoryRecord->qty) {
                            $fail("The total assigned quantity ($total) exceeds the available stock ({$inventoryRecord->qty}).");
                        }
                    };
                })
                ->minItems(1)
                ->columnSpanFull()
                ->columns(4),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('inventoryRecord.temp_serial')
                    ->label('Uniform Serial Number')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('uniform_lines')
                    ->label('Uniform Details')
                    ->getStateUsing(function ($record) {
                        $details = is_array($record->details) ? $record->details : json_decode($record->details, true);

                        if (!is_array($details)) {
                            return [];
                        }

                        $courses = Course::pluck('name', 'id');

                        return collect($details)->map(function ($item) use ($courses) {
                            $type = $item['uniform_type'] ?? '-';
                            $size = $item['size'] ?? '-';
                            $course = $courses[$item['course_id']] ?? '-';
                            $qty = $item['quantity'] ?? '-';

                            return "Type: $type | Size: $size | Course: $course | Quantity: $qty";
                        })->values()->all();
                    })
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->wrap()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Added At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])

            ->filters([
                TrashedFilter::make(),
                Filter::make('created_today')
                    ->label('Added Today')
                    ->query(fn ($query) => $query->whereDate('created_at', now()->toDateString())),

                Filter::make('recent')
                    ->label('Last 7 Days')
                    ->query(fn ($query) => $query->where('created_at', '>=', now()->subDays(7))),
                        Filter::make('created_at_range')
                    ->label('Created At Range')
                    ->form([
                        DatePicker::make('from')
                            ->label('From'),
                        DatePicker::make('until')
                            ->label('Until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($query, $date) => $query->where('created_at', '>=', $date))
                            ->when($data['until'], fn ($query, $date) => $query->where('created_at', '<=', $date));
                    }),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(UniformInventoryExporter::class)
                    ->label('Export Inventory Records'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                ]),
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
