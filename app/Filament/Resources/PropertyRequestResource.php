<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PropertyRequestResource\Pages;
use App\Filament\Resources\PropertyRequestResource\RelationManagers;
use App\Models\PropertyRequest;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;


class PropertyRequestResource extends Resource
{
    protected static ?string $model = PropertyRequest::class;
    protected static ?string $navigationGroup = 'Property Management';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category')
                    ->required()
                    ->preload()
                    ->searchable(),

                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->minValue(1),

                RichEditor::make('purpose')
                    ->label('Item Requests')
                    ->disableToolbarButtons([
                        'strike',
                        'codeblock',
                        'attachFiles'
                    ])
                    ->columnSpanFull(),

                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->visible(fn () => \Filament\Facades\Filament::auth()->user()?->role === 'admin'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name')->label('Category'),
                TextColumn::make('user.name')->label('Requested By'),
                TextColumn::make('quantity'),
                TextColumn::make('status')->badge()->color(fn($state) => match($state) {
                    'approved' => 'success',
                    'pending' => 'warning',
                    'rejected' => 'danger',
                }),
                TextColumn::make('created_at')->dateTime()->label('Requested At'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->action(function ($record) {
                        if ($record->inventoryRecord->qty >= $record->quantity) {
                            $record->inventoryRecord->decrement('qty', $record->quantity);
                            $record->update(['status' => 'approved']);
                        } else {
                            Notification::make()
                                ->title('Not enough stock')
                                ->danger()
                                ->send();
                        }
                    }),

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
            'index' => Pages\ListPropertyRequests::route('/'),
            'create' => Pages\CreatePropertyRequest::route('/create'),
            'edit' => Pages\EditPropertyRequest::route('/{record}/edit'),
        ];
    }
}
