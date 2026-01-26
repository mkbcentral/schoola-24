<?php

namespace App\Console\Commands;

use App\Domain\Helpers\SmsNotificationHelper;
use Exception;
use Illuminate\Console\Command;

class TestOrangeSmsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:test
                            {phone : Le numéro de téléphone destinataire (format: +243...)}
                            {message? : Le message à envoyer (optionnel)}
                            {--balance : Afficher le solde SMS uniquement}
                            {--stats : Afficher les statistiques d\'usage}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Teste l\'envoi de SMS via l\'API Orange ou affiche le solde/statistiques';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Test de l\'API Orange SMS');
        $this->newLine();

        // Vérification de la configuration
        if (!$this->checkConfiguration()) {
            return Command::FAILURE;
        }

        try {
            // Option --balance : Afficher le solde uniquement
            if ($this->option('balance')) {
                return $this->displayBalance();
            }

            // Option --stats : Afficher les statistiques
            if ($this->option('stats')) {
                return $this->displayStatistics();
            }

            // Envoi d'un SMS de test
            return $this->sendTestSms();

        } catch (Exception $e) {
            $this->error('❌ Erreur : ' . $e->getMessage());
            $this->newLine();

            if ($this->output->isVerbose()) {
                $this->line($e->getTraceAsString());
            }

            return Command::FAILURE;
        }
    }

    /**
     * Vérifie que la configuration Orange SMS est présente
     */
    private function checkConfiguration(): bool
    {
        $this->info('🔍 Vérification de la configuration...');

        $clientId = config('services.orange_sms.client_id');
        $clientSecret = config('services.orange_sms.client_secret');
        $senderPhone = config('services.orange_sms.sender_phone');

        if (empty($clientId) || empty($clientSecret)) {
            $this->error('❌ Configuration manquante !');
            $this->warn('Veuillez définir les variables suivantes dans .env :');
            $this->line('  - ORANGE_SMS_CLIENT_ID');
            $this->line('  - ORANGE_SMS_CLIENT_SECRET');
            $this->line('  - ORANGE_SMS_SENDER_PHONE');
            $this->newLine();
            return false;
        }

        $this->info('✅ Configuration OK');
        $this->table(
            ['Paramètre', 'Valeur'],
            [
                ['Client ID', substr($clientId, 0, 20) . '...'],
                ['Client Secret', '***' . substr($clientSecret, -8)],
                ['Sender Phone', $senderPhone],
                ['Country Code', config('services.orange_sms.country_code', 'COD')],
            ]
        );
        $this->newLine();

        return true;
    }

    /**
     * Affiche le solde SMS disponible
     */
    private function displayBalance(): int
    {
        $this->info('💰 Vérification du solde SMS...');
        $this->newLine();

        $countryCode = config('services.orange_sms.country_code', 'COD');
        $balance = SmsNotificationHelper::checkBalance($countryCode);

        if (empty($balance['contracts'])) {
            $this->warn('⚠️  Aucun contrat trouvé');
            return Command::SUCCESS;
        }

        foreach ($balance['contracts'] as $contract) {
            $this->info('📊 Contrat pour ' . $contract['country']);
            $this->table(
                ['Paramètre', 'Valeur'],
                [
                    ['Unités disponibles', $contract['availableUnits']],
                    ['Status', $contract['status']],
                    ['Type d\'offre', $contract['offerName']],
                    ['Expiration', $contract['expirationDate']],
                    ['Dernière MAJ', $contract['lastUpdateDate']],
                ]
            );
            $this->newLine();
        }

        return Command::SUCCESS;
    }

    /**
     * Affiche les statistiques d'usage
     */
    private function displayStatistics(): int
    {
        $this->info('📈 Récupération des statistiques d\'usage...');
        $this->newLine();

        $countryCode = config('services.orange_sms.country_code', 'COD');
        $stats = SmsNotificationHelper::getUsageStatistics($countryCode);

        $partnerStats = $stats['statistics']['partnerStatistics'] ?? null;

        if (!$partnerStats) {
            $this->warn('⚠️  Aucune statistique disponible');
            return Command::SUCCESS;
        }

        $this->info('Developer ID: ' . $partnerStats['developerId']);
        $this->newLine();

        foreach ($partnerStats['statistics'] as $serviceStat) {
            $this->info('📱 Service: ' . $serviceStat['service']);

            foreach ($serviceStat['serviceStatistics'] as $countryStat) {
                $country = $countryStat['country'];

                $this->line('  Pays: ' . $country);

                foreach ($countryStat['countryStatistics'] as $appStat) {
                    $this->table(
                        ['App ID', 'Usage', 'Enforcements'],
                        [
                            [
                                $appStat['appid'],
                                $appStat['usage'],
                                $appStat['nbEnforcements']
                            ]
                        ]
                    );
                }
            }
            $this->newLine();
        }

        return Command::SUCCESS;
    }

    /**
     * Envoie un SMS de test
     */
    private function sendTestSms(): int
    {
        $phone = $this->argument('phone');
        $message = $this->argument('message') ?? 'Test SMS depuis Schoola - ' . now()->format('d/m/Y H:i:s');

        // Validation du format du numéro
        if (!preg_match('/^\+\d{10,15}$/', $phone)) {
            $this->error('❌ Format du numéro invalide !');
            $this->warn('Le numéro doit être au format international : +243971330007');
            $this->newLine();
            return Command::FAILURE;
        }

        $this->info('📤 Envoi du SMS en cours...');
        $this->line('  Destinataire : ' . $phone);
        $this->line('  Message : ' . $message);
        $this->newLine();

        // Confirmation avant envoi
        if (!$this->confirm('Continuer l\'envoi ?', true)) {
            $this->warn('⚠️  Envoi annulé');
            return Command::SUCCESS;
        }

        // Envoi du SMS
        $response = SmsNotificationHelper::sendOrangeSMS($phone, $message);

        // Affichage du résultat
        $this->info('✅ SMS envoyé avec succès !');
        $this->newLine();

        $this->table(
            ['Paramètre', 'Valeur'],
            [
                ['Resource ID', $response['resource_id']],
                ['Status HTTP', $response['status']],
                ['Destinataire', $phone],
                ['Horodatage', now()->format('d/m/Y H:i:s')],
            ]
        );

        $this->newLine();
        $this->info('💡 Conseil : Conservez le Resource ID pour le tracking et les Delivery Receipts');
        $this->newLine();

        return Command::SUCCESS;
    }
}
