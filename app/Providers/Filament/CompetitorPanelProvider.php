<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Competition;
use App\Livewire\PersonalInfo;
use App\Livewire\ProfileInfo;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Guava\FilamentKnowledgeBase\KnowledgeBasePlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class CompetitorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('competitor')
            ->path('competitor')
            ->databaseNotifications()
            ->spa()
            ->emailVerification()
            ->viteTheme('resources/css/filament/competitor/theme.css')
            ->colors([
                'primary' => Color::Sky,
            ])
            ->discoverResources(
                in: app_path('Filament/Competitor/Resources'),
                for: 'App\\Filament\\Competitor\\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Competitor/Pages'),
                for: 'App\\Filament\\Competitor\\Pages'
            )
            ->pages([
                Competition::class
            ])
            ->discoverWidgets(
                in: app_path('Filament/Competitor/Widgets'),
                for: 'App\\Filament\\Competitor\\Widgets'
            )
            ->plugins([
                KnowledgeBasePlugin::make(),
                BreezyCore::make()
                    ->myProfile()
                    ->myProfileComponents([
                        'personal_info' => PersonalInfo::class,
                        ProfileInfo::class
                    ])
                    ->enableTwoFactorAuthentication(),
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
            ])
            ->authMiddleware([Authenticate::class]);
    }
}
