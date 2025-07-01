<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UniformDistributionResource\Pages;
use App\Models\UniformDistribution;
use App\Models\UniformSize;
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
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationIcon = 'heroicon-s-identification';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('student_identification_id')
                    ->label('Student ID')
                    ->options(UniformSize::query()
                        ->orderBy('student_identification')
                        ->pluck('student_identification', 'id'))
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function ($state, Set $set) {
                        $uniformSize = UniformSize::find($state);

                        if (! $uniformSize || ! is_array($uniformSize->sizes)) {
                            return;
                        }

                        $set('sizes_id', collect($uniformSize->sizes)->values()->toArray());
                    })
                    ->required(),

                TextInput::make('receipt_number')
                    ->label('Receipt Number')
                    ->required(),

                CheckboxList::make('sizes_id')
                    ->label('Uniform Sizes')
                    ->options(function (Get $get) {
                        $uniformSize = UniformSize::find($get('student_identification_id'));

                        if (! $uniformSize || ! is_array($uniformSize->sizes)) {
                            return [];
                        }

                        return collect($uniformSize->sizes)->mapWithKeys(function ($size, $index) {
                            $label = "Type: {$size['uniform_type']} | Size: {$size['size']} | Quantity: {$size['quantity']}";
                            return [$index => $label];
                        })->toArray();
                    })
                    ->columns(1)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('studentIdentification.student_identification')
                    ->label('Student ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('studentIdentification.student_name')
                    ->label('Student Name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('studentIdentification.department.name')
                    ->label('Department')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('studentIdentification.course.name')
                    ->label('Course')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('receipt_number')
                    ->label('Receipt Number')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Date Issued')
                    ->date()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                TrashedFilter::make(),

                SelectFilter::make('department_id')
                    ->label('Department')
                    ->relationship('studentIdentification.department', 'name'),
    
                SelectFilter::make('course_id')
                    ->label('Course')
                    ->relationship('studentIdentification.course', 'name'),
    
                SelectFilter::make('student_identification_id')
                    ->label('Student')
                    ->relationship('studentIdentification', 'student_name')
                    ->searchable(),
    
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
            'index' => Pages\ListUniformDistributions::route('/'),
            'create' => Pages\CreateUniformDistribution::route('/create'),
            'edit' => Pages\EditUniformDistribution::route('/{record}/edit'),
        ];
    }
}
