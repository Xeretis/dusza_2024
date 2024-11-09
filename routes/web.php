<?php

use App\Livewire\AcceptInvitation;
use App\Livewire\LandingPage;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingPage::class);
Route::get('/accept-invitation', AcceptInvitation::class)->middleware('guest')->name('accept-invitation');

Route::get('/login', function () {
    return redirect(Filament::getPanel('common')->getLoginUrl());
})->name('login');

