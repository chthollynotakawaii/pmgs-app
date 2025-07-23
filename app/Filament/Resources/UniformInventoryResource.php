<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UniformInventoryResource\Pages;
use App\Models\UniformInventory;
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
use Filament\Forms\Get;

class UniformInventoryResource extends Resource
{
    protected static ?string $model = UniformInventory::class;
    protected static ?string $label = 'Uniform Inventory';
    protected static ?string $pluralLabel = 'Uniform Inventory';
    protected static ?string $navigationGroup = 'Uniform Management';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-s-clipboard';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('course_id')
                ->label('Course')
                ->options(Course::all()->pluck('name', 'id'))
                ->searchable()
                ->preload()
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
                            'COVERALL' => 'COVERALL',
                            'GOGGLES' => 'GOGGLES',
                            'HELMET' => 'HELMET',
                            'BAG' => 'BAG',
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
                            'XXXL' => 'XXXL',
                            '4XL' => '4XL',
                            '5XL' => '5XL',
                            '6XL' => '6XL',
                        ])
                        ->searchable()
                        ->required(),

                    TextInput::make('quantity')
                        ->label('Quantity')
                        ->numeric()
                        ->minValue(1)
                        ->default(1)
                        ->required(),
                ])
                ->rule(function (callable $get) {
                    return function (string $attribute, $value, \Closure $fail) use ($get) {
                        $combos = collect($value ?? [])
                            ->map(fn ($item) => ($item['uniform_type'] ?? '') . '|' . ($item['size'] ?? ''))
                            ->filter()
                            ->toArray();

                        if (count($combos) !== count(array_unique($combos))) {
                            $fail('Duplicate combinations of uniform type and size are not allowed.');
                        }
                    };
                })
                ->disableItemDeletion(fn (Get $get) => $get('id') !== null)
                ->minItems(1)
                ->columns(3)
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('course.name')
                    ->label('Course')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('uniform_lines')
                    ->label('Uniform Details')
                    ->getStateUsing(function ($record) {
                        $details = is_array($record->details) ? $record->details : json_decode($record->details, true);

                        return collect($details)->map(function ($item) {
                            $type = $item['uniform_type'] ?? '-';
                            $size = $item['size'] ?? '-';
                            $qty = $item['quantity'] ?? '-';
                            return "Type: $type | Size: $size | Quantity: $qty";
                        })->values()->all();
                    })
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->wrap(),

                TextColumn::make('created_at')
                    ->label('Added At')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable(),
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
                        DatePicker::make('from')->label('From'),
                        DatePicker::make('until')->label('Until'),
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
