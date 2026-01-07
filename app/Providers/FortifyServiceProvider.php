<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // Vues d'authentification personnalisées avec Livewire
        Fortify::loginView(function () {
            return view('guest-view');
        });

        Fortify::registerView(function () {
            return view('auth.register-view');
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password-view');
        });

        Fortify::resetPasswordView(function ($request) {
            return view('auth.reset-password-view', ['request' => $request]);
        });

        Fortify::verifyEmailView(function () {
            return view('auth.verify-email-view');
        });

        Fortify::twoFactorChallengeView(function () {
            return view('auth.two-factor-challenge-view');
        });

        Fortify::confirmPasswordView(function () {
            return view('auth.confirm-password-view');
        });
    }
}
