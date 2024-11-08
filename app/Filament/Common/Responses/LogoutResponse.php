<?php

namespace App\Filament\Common\Responses;

use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LogoutResponse implements LogoutResponseContract
{
    public function toResponse($request): Response|RedirectResponse
    {
        Session::forget('url.intended');

        return redirect()->to(Filament::getPanel('common')->login()->getUrl());
    }
}
