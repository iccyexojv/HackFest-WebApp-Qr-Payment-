<?php

namespace App\Providers\Filament;

use App\Filament\Pages\QuickPayPayment;
use App\Filament\Pages\QuickPayQr;
use App\Filament\Pages\TransactionHistory;
use App\Filament\Participant\Pages\ParticipantRegisterPage;
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

class ParticipantPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('participant')
            ->path('participant')
            ->viteTheme('resources/css/filament/participant/theme.css')
            ->login()
            ->registration(ParticipantRegisterPage::class)
            ->profile()
            ->passwordReset()
            ->authPasswordBroker('participants')
            ->authGuard('participant')
            ->colors([
                'primary' => Color::Blue,
            ])

            ->font("poopins")
            ->discoverResources(in: app_path('Filament/Participant/Resources'), for: 'App\\Filament\\Participant\\Resources')
            ->discoverPages(in: app_path('Filament/Participant/Pages'), for: 'App\\Filament\\Participant\\Pages')
            ->pages([
                Pages\Dashboard::class,
                TransactionHistory::class,
                QuickPayQr::class,
                QuickPayPayment::class,

            ])
            ->discoverWidgets(in: app_path('Filament/Participant/Widgets'), for: 'App\\Filament\\Participant\\Widgets')
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
