<?php

namespace App\Providers\Filament;

use App\Filament\Resources\Auth\CustomLogin;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Hardikkhorasiya09\ChangePassword\ChangePasswordPlugin;
// use DiogoGPinto\AuthUIEnhancer\AuthUIEnhancerPlugin;
use Filament\View\PanelsRenderHook;
use Filament\Support\Facades\FilamentView;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        FilamentView::registerRenderHook(
        PanelsRenderHook::HEAD_END,
        fn () => '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>'
        );
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('PMGS')
            // ->topNavigation()
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth('full')
            ->font('poppins')
            ->favicon(asset('images/logo.png'))
            ->login(CustomLogin::class)
            ->colors([
                'primary' => '#0434fd',
                'secondary' => '#f2c74d',
                'success' => Color::Green,
                'danger' => Color::Red,
                'warning' => Color::Yellow,
                'info' => Color::Cyan,
            ])
            // ->theme(asset('css/filament/admin/theme.css'))
            ->databaseNotifications()
            ->discoverResources(
                in: app_path('Filament/Resources'),
                for: 'App\\Filament\\Resources',
            )
            ->discoverPages(
                in: app_path('Filament/Pages'),
                for: 'App\\Filament\\Pages',
            )
            ->pages([])
            ->discoverWidgets(
                in: app_path('Filament/Widgets'),
                for: 'App\\Filament\\Widgets',
            )
            ->plugins([
                ChangePasswordPlugin::make(),
                // AuthUIEnhancerPlugin::make()
                // ->showEmptyPanelOnMobile(false)
                // ->formPanelWidth('25%')
                // ->formPanelBackgroundColor(Color::hex('#f2c74d'))
                // ->emptyPanelBackgroundImageUrl(asset('images/mmaci-bg.svg')),
                
                ])
            ->widgets([])
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
