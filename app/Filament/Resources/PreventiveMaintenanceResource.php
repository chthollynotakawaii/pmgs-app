<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PreventiveMaintenanceResource\Pages;
use App\Filament\Resources\PreventiveMaintenanceResource\RelationManagers;
use App\Models\InventoryRecord;
use App\Models\PreventiveMaintenance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class PreventiveMaintenanceResource extends Resource
{
    protected static ?string $model = PreventiveMaintenance::class;
    protected static ?string $navigationGroup = 'Property Management';
    protected static ?string $navigationIcon = 'heroicon-s-wrench-screwdriver';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Select::make('inventory_record_ids')
                ->label('Inventory Items')
                ->multiple()
                ->options(
                    \App\Models\InventoryRecord::with(['department', 'location'])
                        ->get()
                        ->mapWithKeys(function ($record) {
                            $dept = $record->department->name ?? 'N/A';
                            $loc = $record->location->name ?? 'N/A';
                            $temp_serial = $record->temp_serial ?? 'N/A';
                            $label = "{$record->control_number} - {$temp_serial} - ({$dept} | {$loc})";
                            return [$record->id => $label];
                        })
                )

                ->preload()
                ->searchable()
                ->columnSpanFull()
                ->required(),

            TextInput::make('maintenance_type')
                ->label('Maintenance Type')
                ->columnSpanFull()
                ->required(),

            DatePicker::make('scheduled_date')
                ->label('Scheduled Maintenance')
                ->columnSpanFull()
                ->required(),

            Textarea::make('remarks')->rows(3)
                ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('maintenance_type')->searchable(),
                TextColumn::make('scheduled_date')->date()->searchable(),
                TextColumn::make('inventory_record_ids')
                    ->label('Items')
                    ->getStateUsing(function ($record) {
                        $ids = is_array($record->inventory_record_ids)
                            ? $record->inventory_record_ids
                            : json_decode($record->inventory_record_ids, true);

                        return collect($ids)->map(function ($id) {
                            $record = InventoryRecord::with(['department', 'location'])->find($id);

                            if (!$record) return null;

                            $control = $record->control_number ?? 'N/A';
                            $dept = $record->department->name ?? 'N/A';
                            $loc = $record->location->name ?? 'N/A';

                            return $control . " ({$dept} | {$loc})";
                        })->filter()->values()->all();
                    })
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->searchable()
                    ->wrap(),
            ])
            ->filters([
            // Filter by maintenance type
                Tables\Filters\SelectFilter::make('maintenance_type')
                    ->label('Maintenance Type')
                    ->options(
                        PreventiveMaintenance::query()
                            ->whereNotNull('maintenance_type')
                            ->distinct()
                            ->pluck('maintenance_type', 'maintenance_type')
                            ->toArray()
                    ),

                // Filter by inventory item
                Tables\Filters\SelectFilter::make('inventory_item')
                    ->label('Inventory Item')
                    ->options(
                        InventoryRecord::query()
                            ->whereNotNull('temp_serial')
                            ->pluck('temp_serial', 'id')
                            ->toArray()
                    )
                    ->query(function (Builder $query, array $data): Builder {
                        return isset($data['value']) && $data['value']
                            ? $query->whereJsonContains('inventory_record_ids', (int) $data['value'])
                            : $query;
                    }),

                // Filter: upcoming maintenance (next 30 days)
                Tables\Filters\TernaryFilter::make('upcoming')
                    ->label('Upcoming (30 days)')
                    ->queries(
                        true: fn ($query) => $query->whereBetween('scheduled_date', [now(), now()->addDays(30)]),
                        false: fn ($query) => $query->whereDate('scheduled_date', '<', now()),
                        blank: fn ($query) => $query,
                    ),

                // Filter: this month
                Tables\Filters\TernaryFilter::make('this_month')
                    ->label('Scheduled This Month')
                    ->queries(
                        true: fn ($query) => $query->whereMonth('scheduled_date', now()->month),
                        false: fn ($query) => $query->whereMonth('scheduled_date', '!=', now()->month),
                        blank: fn ($query) => $query,
                    ),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPreventiveMaintenances::route('/'),
            'create' => Pages\CreatePreventiveMaintenance::route('/create'),
            'edit' => Pages\EditPreventiveMaintenance::route('/{record}/edit'),
        ];
    }
}
