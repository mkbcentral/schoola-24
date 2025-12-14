<?php

namespace App\Console\Commands;

use App\Services\Student\DerogationTrackerService;
use Illuminate\Console\Command;

class CheckDerogationStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'derogation:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifie et désactive les dérogations expirées chaque jour';

    public function handle(DerogationTrackerService $service)
    {
        $service->checkAndDisableExpiredDerogations();
        $this->info('Vérification des dérogations expirées terminée.');
    }
}
