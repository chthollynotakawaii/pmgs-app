<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UniformDistributionResource\Pages;
use App\Models\UniformDistribution;
use App\Models\UniformSize;
use App\Models\UniformStockSummary;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;

class UniformDistributionResource extends Resource
{
    protected static ?string $model = UniformDistribution::class;
    protected static ?string $navigationGroup = 'Uniform Management';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationIcon = 'heroicon-s-identification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('student_name_id')
                    ->label('Student Name')
                    ->relationship(
                        name: 'uniformSize',
                        titleAttribute: 'student_name',
                        modifyQueryUsing: fn ($query) => $query->whereDoesntHave('uniformDistribution')
                    )
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function ($state, Set $set) {
                        $uniformSize = UniformSize::find($state);
                        if (! $uniformSize) return;
                        $set('sizes_id', []);
                    })
                    ->preload()
                    ->visibleOn('create')
                    ->columnSpanFull()
                    ->required(),

                TextInput::make('receipt_number')
                    ->label('Receipt Number')
                    ->columnSpanFull(),

                CheckboxList::make('sizes_id')
                    ->label('Uniform Sizes')
                    ->options(function (Get $get) {
                        $uniformSize = \App\Models\UniformSize::find($get('student_name_id'));

                        $sizes = is_array($uniformSize?->sizes)
                            ? $uniformSize->sizes
                            : json_decode($uniformSize?->sizes ?? '[]', true);

                        return collect($sizes)
                            ->mapWithKeys(function ($item) {
                                $key = base64_encode(json_encode($item)); // Safe unique string key
                                $label = "Type: {$item['uniform_type']} | Size: {$item['size']} | Qty: {$item['quantity']}";
                                return [$key => $label];
                            })
                            ->toArray();
                    })
                    ->afterStateHydrated(function (Set $set, Get $get, $state) {
                        if (! is_array($state)) return;

                        $encoded = collect($state)
                            ->map(fn ($item) => base64_encode(json_encode($item)))
                            ->toArray();

                        $set('sizes_id', $encoded);
                    })
                    ->dehydrateStateUsing(function (?array $state) {
                        return collect($state)
                            ->map(fn ($encoded) => json_decode(base64_decode($encoded), true))
                            ->toArray();
                    })
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('uniformSize.student_identification')->label('Student ID')->sortable()->searchable(),
                TextColumn::make('uniformSize.student_name')->label('Student Name')->sortable()->searchable(),
                TextColumn::make('uniformSize.department.name')->label('Department')->sortable()->searchable(),
                TextColumn::make('uniformSize.course.name')->label('Course')->sortable()->searchable(),
                TextColumn::make('receipt_number')->label('Receipt Number')->sortable()->searchable(),
                TextColumn::make('created_at')->label('Date Issued')->datetime()->sortable(),
                TextColumn::make('updated_at')->label('Date Updated')->datetime()->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('department_id')->label('Department')->relationship('uniformSize.department', 'name'),
                SelectFilter::make('course_id')->label('Course')->relationship('uniformSize.course', 'name'),
                SelectFilter::make('student_name')->label('Student')->relationship('uniformSize', 'student_name')->searchable(),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')->label('From'),
                        DatePicker::make('until')->label('Until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('created_at', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->whereDate('created_at', '<=', $data['until']));
                    }),
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

    public static function getRelations(): array
    {
        return [];
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
