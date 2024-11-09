<?php

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;
use App\Livewire\LandingPage;

Route::get('/landing',LandingPage::class);

Route::get('/', function () {
    return redirect(Filament::getPanel('common')->getUrl());
});

Route::get('/login', function () {
    return redirect(Filament::getPanel('common')->getLoginUrl());
})->name('login');

