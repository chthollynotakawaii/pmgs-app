<?php  
namespace App\Filament\Pages;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    protected static ?string $navigationIcon = 'heroicon-o-home';
    

    public function filtersForm(Form $form): Form
    {
        return $form->schema([
            Section::make('Filters')
                ->description('Use these filters to narrow down the data displayed on the dashboard.')
                ->label('Dashboard Filters')
                ->collapsible()
                ->columns(2)
                ->schema([
                    DatePicker::make('start_date')
                        ->label('Start Date')
                        ->default(now()->startOfMonth())
                        ->required(),

                    DatePicker::make('end_date')
                        ->label('End Date')
                        ->default(now()->endOfDay())
                        ->required(),
                ]),
        ]);
    }
}   