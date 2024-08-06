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
        return Auth::attempt($credentials);
    }

    /**
     * @inheritDoc
     */
    public static function register(array $inputs)
    {
    }
}
