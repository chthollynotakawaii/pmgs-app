<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UniformSizeResource\Pages;
use App\Models\UniformSize;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ExportAction;
use App\Filament\Exports\UniformSizeExporter;

class UniformSizeResource extends Resource
{
    protected static ?string $model = UniformSize::class;
    protected static ?string $navigationGroup = 'Inventory Management';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationIcon = 'heroicon-s-bars-arrow-down';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('student_name')
                    ->label('Student Name')
                    ->columnSpan(1),

                TextInput::make('student_identification')
                    ->label('Student ID')
                    ->maxLength(11)
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
                    ->preload(),

                Repeater::make('sizes')
                    ->label('Uniform Sizes')
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

                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->minValue(1)
                            ->default(1)
                            ->numeric()
                            ->integer()
                            ->required(),
                    ])
                    ->minItems(1)
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student_name')->label('Student Name')->searchable()->sortable()->toggleable(),
                TextColumn::make('student_identification')->label('Student ID')->searchable()->sortable()->toggleable(),
                TextColumn::make('department.name')->label('Department')->sortable()->toggleable(),
                TextColumn::make('course.name')->label('Course')->sortable()->toggleable(),
                TextColumn::make('sizes')
                    ->label('Uniform Sizes')
                    ->getStateUsing(function ($record) {
                        $details = is_array($record->sizes) ? $record->sizes : json_decode($record->sizes, true);

                        if (!is_array($details)) {
                            return [];
                        }

                        return collect($details)->map(function ($item) {
                            $type = $item['uniform_type'] ?? '-';
                            $size = $item['size'] ?? '-';
                            $qty = $item['quantity'] ?? '-';

                            return "Type: $type | Size: $size | Quantity: $qty";
                        })->values()->all();
                    })
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->wrap()
                    ->toggleable(),
            ])
            ->filters([
                TrashedFilter::make(),
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
