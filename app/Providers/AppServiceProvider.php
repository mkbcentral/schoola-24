<?php

namespace App\Providers;

use App\Enums\RoleType;
use App\Events\RegistrationCreatedEvent;
use App\Listeners\CreateRegistrationPaymentListner;
use App\Listeners\LogSuccessfulLogin;
use App\Listeners\LogSuccessfulLogout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Services\Contracts\CurrencyExchangeServiceInterface;
use App\Services\Contracts\ExpenseServiceInterface;
use App\Services\Contracts\OtherExpenseServiceInterface;
use App\Services\CurrencyExchangeService;
use App\Services\ExpenseService;
use App\Services\OtherExpenseService;
use App\Services\Expense\CategoryExpenseServiceInterface;
use App\Services\Expense\CategoryExpenseService;
use App\Services\Expense\OtherSourceExpenseServiceInterface;
use App\Services\Expense\OtherSourceExpenseService;
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
        // Enregistrer les services de gestion des devises
        $this->app->singleton(CurrencyExchangeServiceInterface::class, CurrencyExchangeService::class);

        // Enregistrer les services de gestion des dépenses
        $this->app->singleton(ExpenseServiceInterface::class, ExpenseService::class);
        $this->app->singleton(OtherExpenseServiceInterface::class, OtherExpenseService::class);

        // Enregistrer les services de gestion des catégories et sources de dépenses
        $this->app->singleton(CategoryExpenseServiceInterface::class, CategoryExpenseService::class);
        $this->app->singleton(OtherSourceExpenseServiceInterface::class, OtherSourceExpenseService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enregistrer les listeners pour la connexion/déconnexion
        Event::listen(
            Login::class,
            LogSuccessfulLogin::class
        );
        Event::listen(
            Logout::class,
            LogSuccessfulLogout::class
        );
        
        // Enregistrer le listener pour la création d'inscription
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

        Gate::define('manage-student', function () {
            return Auth::user()->role->is_for_school == true &&
                Auth::user()->role->name == RoleType::SCHOOL_SECRETARY;
        });
        Gate::define('manage-payment', function () {
            return Auth::user()->role->is_for_school == true &&
                Auth::user()->role->name == RoleType::SCHOOL_FINANCE;
        });
        Gate::define('manage-school', function () {
            return Auth::user()->role->is_for_school == true &&
                Auth::user()->role->name == RoleType::SCHOOL_MANAGER;
        });
        Gate::define('manage-school-app', function () {
            return Auth::user()->role->is_for_school == true &&
                Auth::user()->role->name == RoleType::ADMIN_SCHOOL;
        });
        Gate::define('view-payment', function () {
            return Auth::user()->role->is_for_school == true &&
                Auth::user()->role->name == RoleType::SCHOOL_MANAGER ||
                Auth::user()->role->name == RoleType::SCHOOL_GUARD ||
                Auth::user()->role->name == RoleType::SCHOOL_FINANCE;
        });
        Gate::define('edit-school-user', function () {
            return Auth::user()->role->is_for_school == true &&
                Auth::user()->role->name == RoleType::ADMIN_SCHOOL;
        });
    }
}
