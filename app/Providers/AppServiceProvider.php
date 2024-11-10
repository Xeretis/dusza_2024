<?php

namespace App\Providers;

use App\Livewire\PersonalInfo;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Guava\FilamentKnowledgeBase\Filament\Panels\KnowledgeBasePanel;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        KnowledgeBasePanel::configureUsing(
            fn(KnowledgeBasePanel $panel) => $panel
                ->viteTheme('resources/css/filament/common/theme.css')
                ->pages([])
                ->colors([
                    'primary' => Color::Blue
                ])
                ->guestAccess()
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_START,
            fn() => Blade::render("@vite('resources/js/livewire_debounce.js')")
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Goofy workaround for breezy bug
        Livewire::component('my-breezy-personal-info', PersonalInfo::class);

        RedirectIfAuthenticated::redirectUsing(function () {
            return Filament::getPanel('common')->getUrl();
        });
    }
}
