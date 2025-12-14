<?php

namespace App\Domain\Contract\Auth;

interface AuthContract
{
    /**
     * Login user
     */
    public static function login(array $credentials): bool;

    /**
     * Register new user
     *
     * @return void
     */
    public static function register(array $inputs);
}
