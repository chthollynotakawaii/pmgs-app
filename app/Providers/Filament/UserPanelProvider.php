<?php

namespace App\Providers\Filament;

use App\Filament\Resources\Auth\CustomLogin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use App\Filament\Resources\InventoryRecordResource;
use App\Filament\Resources\BorrowingLogResource;
use App\Filament\Widgets\InventoryOverviewChart;
use App\Filament\Widgets\StatusCard;
use App\Filament\Widgets\UpcomingMaintenanceWidget;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Hardikkhorasiya09\ChangePassword\ChangePasswordPlugin;
use Filament\View\PanelsRenderHook;
use Filament\Support\Facades\FilamentView;

class UserPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        FilamentView::registerRenderHook(
        PanelsRenderHook::HEAD_END,
        fn () => '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>'
        );
        return $panel
            ->id('user')
            ->path('user')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth('full')
            ->font('poppins')
            ->favicon(asset('images/logo.png'))
            ->login(CustomLogin::class)
            ->discoverResources(in: app_path('Filament/User/Resources'), for: 'App\\Filament\\User\\Resources')
            ->discoverPages(in: app_path('Filament/User/Pages'), for: 'App\\Filament\\User\\Pages')
            ->pages([
                Pages\Dashboard::class,
                \App\Filament\Pages\ScanQr::class,
            ])
            ->discoverWidgets(in: app_path('Filament/User/Widgets'), for: 'App\\Filament\\User\\Widgets')
            ->widgets([
                StatusCard::class,
                InventoryOverviewChart::class,
                UpcomingMaintenanceWidget::class,
            ])  
            ->resources([
                InventoryRecordResource::class,
                BorrowingLogResource::class,
            ])
            ->plugins([
                ChangePasswordPlugin::make(),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \App\Http\Middleware\TrackLastSeen::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
