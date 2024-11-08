<?php

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(Filament::getPanel('common')->getUrl());
});

Route::get('/login', function () {
    return redirect(Filament::getPanel('common')->getLoginUrl());
})->name('login');

