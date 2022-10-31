<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\RequirePassword as Middleware;

class RequirePassword extends Middleware
{
    /**
     * Determine if the confirmation timeout has expired.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|null  $passwordTimeoutSeconds
     * @return bool
     */
    protected function shouldConfirmPassword($request, $passwordTimeoutSeconds = null)
    {
        if ($request->session()->has('Socialite')) {
            return false;
        }

        return parent::shouldConfirmPassword($request, $passwordTimeoutSeconds);
    }
}
