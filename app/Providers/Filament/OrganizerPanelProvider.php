<?php

namespace App\Providers\Filament;

use App\Livewire\PersonalInfo;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
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

class OrganizerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('organizer')
            ->path('organizer')
            ->spa()
            ->emailVerification()
            ->viteTheme('resources/css/filament/organizer/theme.css')
            ->colors([
                'primary' => Color::Orange,
            ])
            ->databaseNotifications()
            ->discoverResources(
                in: app_path('Filament/Organizer/Resources'),
                for: 'App\\Filament\\Organizer\\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Organizer/Pages'),
                for: 'App\\Filament\\Organizer\\Pages'
            )
            ->pages([Pages\Dashboard::class])
            ->discoverWidgets(
                in: app_path('Filament/Organizer/Widgets'),
                for: 'App\\Filament\\Organizer\\Widgets'
            )
            ->navigationGroups(['Résztvevők', 'Verseny'])
            ->plugins([KnowledgeBasePlugin::make(), BreezyCore::make()->myProfile()->myProfileComponents([
                'personal_info' => PersonalInfo::class
            ])->enableTwoFactorAuthentication(),])
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
