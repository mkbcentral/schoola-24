<?php

namespace App\Domain\Features\AuthFeatue;

use App\Domain\Contract\Auth\AuthContract;
use Illuminate\Support\Facades\Auth;

class AuthFeature implements AuthContract
{

    /**
     * @inheritDoc
     */
    public static function login(array $credentials): bool
    {
        $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        return Auth::attempt([
            $loginField => $credentials['login'],
            'password' => $credentials['password'],
        ]);
    }





    /**
     * @inheritDoc
     */
    public static function register(array $inputs) {}
}
