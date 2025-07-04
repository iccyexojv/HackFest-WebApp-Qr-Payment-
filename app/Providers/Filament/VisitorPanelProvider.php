<?php

namespace App\Providers\Filament;

use App\Filament\Pages\QuickPayPayment;
use App\Filament\Pages\QuickPayQr;
use App\Filament\Pages\TransactionHistory;
use App\Filament\Visitor\Pages\VisitorRegisterPage;
use App\Filament\Widgets\WalletsOverviewWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class VisitorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('visitor')
            ->path('visitor')
            ->viteTheme('resources/css/filament/visitor/theme.css')
            ->login()
            ->registration(VisitorRegisterPage::class)
            ->passwordReset()
            ->profile()
            // ->topNavigation()
            ->authPasswordBroker('visitors')
            ->authGuard('visitor')

            ->colors([
                'primary' => Color::Green,
            ])
            ->font("poopins")
            ->discoverResources(in: app_path('Filament/Visitor/Resources'), for: 'App\\Filament\\Visitor\\Resources')
            ->discoverPages(in: app_path('Filament/Visitor/Pages'), for: 'App\\Filament\\Visitor\\Pages')
            ->pages([
                Pages\Dashboard::class,
                TransactionHistory::class,
                QuickPayQr::class,
                QuickPayPayment::class,

            ])
            ->discoverWidgets(in: app_path('Filament/Visitor/Widgets'), for: 'App\\Filament\\Visitor\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
                WalletsOverviewWidget::class,

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
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class

            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                \Hasnayeen\Themes\ThemesPlugin::make()
                    ->canViewThemesPage(fn() => false),
            ]);
    }
}
