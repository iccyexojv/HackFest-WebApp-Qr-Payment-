<?php

namespace App\Providers\Filament;

use App\Filament\Pages\QuickPayPayment;
use App\Filament\Pages\QuickPayQr;
use App\Filament\Pages\TransactionHistory;
use App\Filament\Stall\Pages\RegisterPage;
use App\Filament\Stall\Resources\StallOwnerResource\Pages\ListStallOwners;
use App\Filament\Widgets\WalletsOverviewWidget;
use App\Models\Stall;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
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

class StallPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('stall')
            ->path('stall')
            ->viteTheme('resources/css/filament/stall/theme.css')
            ->login()
            ->registration(RegisterPage::class)
            ->passwordReset()
            ->profile()
            ->authPasswordBroker('stalls')
            ->authGuard('stall')
            ->colors([
                'primary' => Color::Red,
            ])
            ->tenant(Stall::class, ownershipRelationship: 'stall')
            ->font("poopins")
            ->discoverResources(in: app_path('Filament/Stall/Resources'), for: 'App\\Filament\\Stall\\Resources')
            ->discoverPages(in: app_path('Filament/Stall/Pages'), for: 'App\\Filament\\Stall\\Pages')
            ->pages([
                Pages\Dashboard::class,
                TransactionHistory::class,
                QuickPayQr::class,
                QuickPayPayment::class,

            ])
            ->discoverWidgets(in: app_path('Filament/Stall/Widgets'), for: 'App\\Filament\\Stall\\Widgets')
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
            ->tenantMiddleware([
                \Hasnayeen\Themes\Http\Middleware\SetTheme::class
            ])
            ->tenantMenuItems([
                MenuItem::make()
                    ->label('Manage Owners')
                    ->url(fn(): string => ListStallOwners::getUrl())
                    ->icon('heroicon-m-users'),
                // ...
            ])
            ->plugins([
                \Hasnayeen\Themes\ThemesPlugin::make()
                    ->canViewThemesPage(fn() => false),
            ]);
    }
}
