<?php

namespace App\Filament\Resources\InventoryRecordResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class BorrowingLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'borrowingLogs';
    protected static ?string $recordTitleAttribute = 'id';
    protected static ?string $title = 'Borrowing Logs';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Log ID')->sortable(),
                TextColumn::make('user.name')->label('User')->sortable()->searchable(),
                TextColumn::make('user.department.name')->label('Department')->sortable()->searchable(),
                TextColumn::make('location.name')->label('Location')->sortable()->searchable(),
                TextColumn::make('recorded_at')->label('Borrowed At')->dateTime()->sortable(),
                TextColumn::make('returned_at')->label('Returned At')->dateTime()->sortable()->placeholder('Not Returned'),
                ToggleColumn::make('remarks')->label('Remarks'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
