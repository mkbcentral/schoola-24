<?php

namespace App\Domain\Contract\Auth;

interface AuthContract
{
    /**
     * Login user
     * @param array $credentials
     * @return bool
     */
    public static function login(array $credentials): bool;
    /**
     * Register new user
     * @param array $inputs
     * @return void
     */
    public static function register(array $inputs);
}
