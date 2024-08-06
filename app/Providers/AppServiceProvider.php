<?php

namespace App\Providers;

use App\Events\RegistrationCreatedEvent;
use App\Listeners\CreateRegistrationPaymentListner;
use App\Listeners\LogSuccessfulLogin;
use App\Listeners\LogSuccessfulLogout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            LogSuccessfulLogin::class,
            LogSuccessfulLogout::class
        );
        Event::listen(
            RegistrationCreatedEvent::class,
            CreateRegistrationPaymentListner::class
        );
        Gate::define('view-school-access', function () {
            return Auth::user()->role->is_for_school == true;
        });
        Gate::define('view-school-unaccess', function () {
            return Auth::user()->role->is_for_school == false;
        });
    }
}
