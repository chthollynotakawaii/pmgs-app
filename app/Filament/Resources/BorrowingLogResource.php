<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BorrowingLogResource\Pages;
use App\Filament\Resources\BorrowingLogResource\Pages\EditBorrowingLog;
use App\Models\BorrowingLog;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Actions\ExportAction;
use App\Filament\Exports\BorrowingLogExporter;
use Carbon\Carbon;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use GuzzleHttp\Promise\Create;

class BorrowingLogResource extends Resource
{
    protected static ?string $model = BorrowingLog::class;
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationBadge = null;

    public static function getNavigationBadge(): ?string
    {
        return (string) BorrowingLog::where('remarks', false)->count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Borrowed Properties not returned';
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('inventory_record_id')
                ->label('Inventory Item')
                ->relationship(
                    name: 'inventoryRecord',
                    titleAttribute: 'temp_serial',
                    modifyQueryUsing: fn ($query) => $query
                        ->where('borrowed', true)
                        ->where('qty', '>', 0)
                )
                ->searchable()
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->temp_serial} (Available: {$record->qty})")
                ->preload()
                ->visibleOn('create')
                ->required(),

            TextInput::make('quantity')
                ->label('Quantity')
                ->numeric()
                ->integer()
                ->minValue(1)
                ->required()
                ->live()
                ->reactive()
                ->rule(function (callable $get) {
                    return function ($attribute, $value, $fail) use ($get) {
                        $inventoryId = $get('inventory_record_id');

                        if (! $inventoryId || ! is_numeric($value)) {
                            return;
                        }

                        $record = \App\Models\InventoryRecord::find($inventoryId);

                        if (! $record) {
                            $fail('The selected inventory item is invalid.');
                            return;
                        }

                        if ($value > $record->qty) {
                            $fail("Only {$record->qty} item(s) are available.");
                        }
                    };
                }),

            Select::make('user_id')
                ->label('Borrower')
                ->relationship('user', 'name', fn ($query) => $query->orderBy('name'))
                ->searchable()
                ->preload()
                ->columnSpan(1)
                ->reactive()
                ->requiredWithout('custom_borrower')
                ->visible(fn ($get) => blank($get('custom_borrower'))),

            TextInput::make('custom_borrower')
                ->label('Borrower (If Student/Staff Not Listed)')
                ->requiredWithout('user_id')
                ->columnSpan(1)
                ->reactive()
                ->visible(fn ($get) => blank($get('user_id'))),

            Select::make('location_id')
                ->label('Location')
                ->relationship('location', 'name', fn ($query) => $query->orderBy('name'))
                ->searchable()
                ->preload()
                ->columnSpan(1)
                ->reactive()
                ->required(),

            DateTimePicker::make('returned_at')
                ->label('Returned At')
                ->default(Carbon::now()->setTime(18, 0, 0))
                ->columnSpan(fn ($get) =>
                    filled($get('user_id')) || filled($get('custom_borrower')) ? 2 : 1
                )
                ->required()
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('inventoryRecord.temp_serial')->label('Item')->sortable()->searchable()->toggleable(),
                TextColumn::make('quantity')->label('Quantity')->sortable()->toggleable(),
                TextColumn::make('inventoryRecord.unit')->label('Unit')->sortable()->searchable()->toggleable(),
                TextColumn::make('inventoryRecord.description')->label('Description')->sortable()->searchable()->toggleable(),
                TextColumn::make('inventoryRecord.brand.name')->label('Brand')->sortable()->searchable()->toggleable(),
                TextColumn::make('inventoryRecord.model.name')->label('Model')->sortable()->searchable()->toggleable(),
                TextColumn::make('inventoryRecord.category.name')->label('Category')->sortable()->searchable()->toggleable(),
                TextColumn::make('inventoryRecord.department.name')->label('Department From')->sortable()->searchable()->toggleable(),
                TextColumn::make('custom_borrower')->label('Custom Borrower')->sortable()->toggleable(),
                TextColumn::make('user.name')->label('Borrower')->sortable()->searchable()->toggleable(),
                TextColumn::make('user.department.name')->label("Borrower's Department")->sortable()->searchable()->toggleable(),
                TextColumn::make('inventoryRecord.location.name')->label('Location From')->sortable()->searchable()->toggleable(),
                TextColumn::make('location.name')->label('Location To')->sortable()->searchable()->toggleable(),
                TextColumn::make('created_at')->label('Borrowed At')->dateTime()->sortable()->toggleable(),
                TextColumn::make('returned_at')->label('Returned At')->dateTime()->sortable()->placeholder('Not Returned')->toggleable(),
                ToggleColumn::make('remarks')->label('Remarks')->sortable()->toggleable(),
            ])
            ->filters([
                TrashedFilter::make(),

                SelectFilter::make('user_id')
                    ->label('Borrower')
                    ->relationship('user', 'name')
                    ->searchable(),

                SelectFilter::make('inventory_record_id')
                    ->label('Item')
                    ->relationship('inventoryRecord', 'temp_serial')
                    ->searchable(),

                SelectFilter::make('location_id')
                    ->label('Location To')
                    ->relationship('location', 'name')
                    ->searchable(),

                Filter::make('borrowed_at')
                    ->label('Borrowed Date Range')
                    ->form([
                        DateTimePicker::make('from'),
                        DateTimePicker::make('until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($query) => $query->where('created_at', '>=', $data['from']))
                            ->when($data['until'], fn ($query) => $query->where('created_at', '<=', $data['until']));
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
            ->headerActions([
                ExportAction::make()
                    ->exporter(BorrowingLogExporter::class)
                    ->label('Export Borrowing Logs')
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
            'index' => Pages\ListBorrowingLogs::route('/'),
            'create' => Pages\CreateBorrowingLog::route('/create'),
            'edit' => Pages\EditBorrowingLog::route('/{record}/edit'),
        ];
    }
}