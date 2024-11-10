<?php

namespace App\Providers\Filament;

use App\Livewire\PersonalInfo;
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

class TeacherPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('teacher')
            ->path('teacher')
            ->spa()
            ->emailVerification()
            ->databaseNotifications()
            ->viteTheme('resources/css/filament/teacher/theme.css')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(
                in: app_path('Filament/Teacher/Resources'),
                for: 'App\\Filament\\Teacher\\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Teacher/Pages'),
                for: 'App\\Filament\\Teacher\\Pages'
            )
            ->discoverWidgets(
                in: app_path('Filament/Teacher/Widgets'),
                for: 'App\\Filament\\Teacher\\Widgets'
            )
            ->plugins([
                KnowledgeBasePlugin::make(),
                BreezyCore::make()
                    ->myProfile()
                    ->myProfileComponents([
                        'personal_info' => PersonalInfo::class,
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
