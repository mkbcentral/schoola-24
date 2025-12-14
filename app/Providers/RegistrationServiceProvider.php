<?php

namespace App\Providers;

use App\Actions\Registration\CreateNewStudentRegistrationAction;
use App\Actions\Registration\CreateRegistrationAction;
use App\Actions\Registration\CreateStudentAction;
use App\Actions\Registration\DeleteRegistrationAction;
use App\Actions\Registration\UpdateRegistrationAction;
use App\Repositories\Registration\RegistrationRepository;
use App\Services\Registration\RegistrationService;
use Illuminate\Support\ServiceProvider;

class RegistrationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register Actions
        $this->app->singleton(CreateStudentAction::class);
        $this->app->singleton(CreateRegistrationAction::class);
        $this->app->singleton(UpdateRegistrationAction::class);
        $this->app->singleton(DeleteRegistrationAction::class);

        // Register CreateNewStudentRegistrationAction with dependencies
        $this->app->singleton(CreateNewStudentRegistrationAction::class, function ($app) {
            return new CreateNewStudentRegistrationAction(
                $app->make(CreateStudentAction::class),
                $app->make(CreateRegistrationAction::class)
            );
        });

        // Register Repository
        $this->app->singleton(RegistrationRepository::class);

        // Register Service with all dependencies
        $this->app->singleton(RegistrationService::class, function ($app) {
            return new RegistrationService(
                $app->make(RegistrationRepository::class),
                $app->make(CreateRegistrationAction::class),
                $app->make(CreateNewStudentRegistrationAction::class),
                $app->make(UpdateRegistrationAction::class),
                $app->make(DeleteRegistrationAction::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
