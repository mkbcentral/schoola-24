<?php

namespace App\Console\Commands;

use App\Services\Subscription\SubscriptionService;
use Illuminate\Console\Command;

class CheckExpiredSubscriptionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifier et mettre à jour les souscriptions expirées';

    /**
     * Execute the console command.
     */
    public function handle(SubscriptionService $subscriptionService): int
    {
        $this->info('Vérification des souscriptions expirées...');

        $expiredCount = $subscriptionService->checkExpiredSubscriptions();

        if ($expiredCount > 0) {
            $this->warn("{$expiredCount} souscription(s) expirée(s) ont été mises à jour.");
        } else {
            $this->info('Aucune souscription expirée trouvée.');
        }

        // Envoyer des notifications pour les souscriptions qui expirent bientôt
        $expiringSoon = $subscriptionService->getExpiringSoonSubscriptions(7);

        if ($expiringSoon->isNotEmpty()) {
            $this->warn("{$expiringSoon->count()} souscription(s) expire(nt) dans les 7 prochains jours:");

            foreach ($expiringSoon as $subscription) {
                $this->line("- {$subscription->school->name} : {$subscription->module->name} (expire le {$subscription->expires_at->format('d/m/Y')})");
            }

            // TODO: Envoyer des emails de notification
        }

        return Command::SUCCESS;
    }
}
