<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UniformSizeResource\Pages;
use App\Models\UniformSize;
use App\Models\UniformStockSummary;
use Filament\Forms\Form;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\ExportAction;
use App\Filament\Exports\UniformSizeExporter;


class UniformSizeResource extends Resource
{
    protected static ?string $model = UniformSize::class;
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationGroup = 'Uniform Management';
    protected static ?string $navigationIcon = 'heroicon-s-bars-arrow-down';

    public static function form(Form $form): Form
    {
        $setAvailableQuantity = function (Set $set, Get $get) {
            $courseId = $get('../../course_id');
            $type = $get('uniform_type');
            $size = $get('size');

            if (!($courseId && $type && $size)) return;

            $available = UniformStockSummary::where([
                'course_id' => $courseId,
                'uniform_type' => $type,
                'size' => $size,
            ])->value('total_quantity') ?? 0;

            $set('available_quantity', $available);
        };

        return $form
            ->schema([
                TextInput::make('student_name')
                    ->label('Student Name')
                    ->required(),

                TextInput::make('student_identification')
                    ->label('Student ID')
                    ->minLength(9)
                    ->numeric()
                    ->integer(),

                Select::make('department_id')
                    ->label('Department')
                    ->relationship('department', 'name', fn ($query) => $query->orderBy('name'))
                    ->searchable()
                    ->preload(),

                Select::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'name', fn ($query) => $query->orderBy('name'))
                    ->searchable()
                    ->preload()
                    ->reactive(),

                Repeater::make('sizes')
                    ->label('Uniform Sizes')
                    ->schema([
                        Select::make('uniform_type')
                            ->label('Uniform Type')
                            ->options(function (Get $get) {
                                $courseId = $get('../../course_id');
                                if (! $courseId) return [];

                                return UniformStockSummary::query()
                                    ->where('course_id', $courseId)
                                    ->distinct('uniform_type')
                                    ->pluck('uniform_type')
                                    ->mapWithKeys(fn ($type) => [$type => $type])
                                    ->toArray();
                            })
                            ->reactive()
                            ->preload()
                            ->required()
                            ->afterStateUpdated(fn (Set $set, Get $get) => $setAvailableQuantity($set, $get)),

                        Select::make('size')
                            ->label('Size')
                            ->options(function (Get $get) {
                                $courseId = $get('../../course_id');
                                $type = $get('uniform_type');
                                if (! $courseId || ! $type) return [];

                                return UniformStockSummary::query()
                                    ->where('course_id', $courseId)
                                    ->where('uniform_type', $type)
                                    ->distinct('size')
                                    ->pluck('size')
                                    ->mapWithKeys(fn ($size) => [$size => $size])
                                    ->toArray();
                            })
                            ->reactive()
                            ->preload()
                            ->required()
                            ->afterStateUpdated(fn (Set $set, Get $get) => $setAvailableQuantity($set, $get)),

                        TextInput::make('quantity')
                            ->label('Requested Quantity')
                            ->minValue(1)
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->rules(fn (Get $get) => [
                                function (string $attribute, $value, $fail) use ($get) {
                                    $courseId = $get('../../course_id');
                                    $type = $get('uniform_type');
                                    $size = $get('size');

                                    if (!($courseId && $type && $size)) return;

                                    $available = UniformStockSummary::query()
                                        ->where([
                                            'course_id' => $courseId,
                                            'uniform_type' => $type,
                                            'size' => $size,
                                        ])
                                        ->value('total_quantity') ?? 0;

                                    if ($value > $available) {
                                        $fail("Requested quantity exceeds available stock ($available)");
                                    }
                                },
                            ]),

                        TextInput::make('available_quantity')
                            ->label('Available Stock')
                            ->disabled()
                            ->dehydrated(false)
                            ->afterStateHydrated(fn (Set $set, Get $get) => $setAvailableQuantity($set, $get))
                            ->suffixIcon(fn (Get $get) => (int) $get('available_quantity') < 5 ? 'heroicon-s-exclamation-circle' : null)
                            ->suffixIconColor(fn (Get $get) => (int) $get('available_quantity') < 5 ? 'danger' : 'success'),
                    ])
                    ->disableItemDeletion(fn (Get $get) => $get('id') !== null)
                    ->minItems(1)
                    ->columns(4)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student_name')->label('Student Name')->searchable()->sortable(),
                TextColumn::make('student_identification')->label('Student ID')->searchable()->sortable(),
                TextColumn::make('department.name')->label('Department')->searchable()->sortable(),
                TextColumn::make('course.name')->label('Course')->sortable(),
                TextColumn::make('sizes')
                    ->label('Uniform Sizes')
                    ->getStateUsing(function ($record) {
                        $details = is_array($record->sizes) ? $record->sizes : json_decode($record->sizes, true);
                        if (!is_array($details)) return [];

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
                Tables\Filters\SelectFilter::make('department_id')
                    ->label('Department')
                    ->relationship('department', 'name')
                    ->searchable(),
                Tables\Filters\SelectFilter::make('course_id')
                    ->label('Course')
                    ->relationship('course', 'name')
                    ->searchable(),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(UniformSizeExporter::class)
                    ->label('Export Uniform Size Records'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                ])
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
            'index' => Pages\ListUniformSizes::route('/'),
            'create' => Pages\CreateUniformSize::route('/create'),
            'edit' => Pages\EditUniformSize::route('/{record}/edit'),
        ];
    }
}
