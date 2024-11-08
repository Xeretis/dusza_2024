<?php

namespace App\Filament\Common\Responses;

use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Routing\Redirector;
use Symfony\Component\HttpFoundation\Response;

/**
 * This overrides the default LoginResponse to redirect to the 'RedirectToOwnPanel' (home) page instead of the dashboard.
 */
class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): Response|Redirector
    {
        return redirect()->intended(Filament::getPanel('common')->getUrl());
    }
}
