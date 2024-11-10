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

class SchoolManagerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('school-manager')
            ->path('school-manager')
            ->spa()
            ->emailVerification()
            ->viteTheme('resources/css/filament/school-manager/theme.css')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(
                in: app_path('Filament/SchoolManager/Resources'),
                for: 'App\\Filament\\SchoolManager\\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/SchoolManager/Pages'),
                for: 'App\\Filament\\SchoolManager\\Pages'
            )
            ->pages([Pages\Dashboard::class])
            ->discoverWidgets(
                in: app_path('Filament/SchoolManager/Widgets'),
                for: 'App\\Filament\\SchoolManager\\Widgets'
            )
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
