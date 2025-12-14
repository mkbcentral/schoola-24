<?php

namespace App\Providers;

use App\Domain\Contract\Payment\IPayment;
use App\Domain\Features\Payment\PaymentFeature;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Repositories\PaymentRepository;
use App\Services\Contracts\PaymentServiceInterface;
use App\Services\PaymentService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Binding de l'interface au repository concret
        $this->app->bind(
            PaymentRepositoryInterface::class,
            PaymentRepository::class
        );

        // Binding des Services
        $this->app->bind(
            PaymentServiceInterface::class,
            PaymentService::class
        );

        // Binding des Features
        $this->app->bind(
            IPayment::class,
            PaymentFeature::class
        );

        // Vous pouvez ajouter d'autres repositories ici
        // $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        // $this->app->bind(RegistrationRepositoryInterface::class, RegistrationRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {}
}
