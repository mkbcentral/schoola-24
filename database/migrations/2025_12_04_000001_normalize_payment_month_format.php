<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Normaliser tous les mois à 2 chiffres avec zéro devant
        $monthMapping = [
            '1' => '01',
            '2' => '02',
            '3' => '03',
            '4' => '04',
            '5' => '05',
            '6' => '06',
            '7' => '07',
            '8' => '08',
            '9' => '09',
        ];

        foreach ($monthMapping as $oldValue => $newValue) {
            DB::table('payments')
                ->where('month', $oldValue)
                ->update(['month' => $newValue]);
        }

        // Log le nombre de lignes affectées
        $affectedRows = 0;
        foreach ($monthMapping as $oldValue => $newValue) {
            $count = DB::table('payments')->where('month', $newValue)->count();
            $affectedRows += $count;
        }

        echo "Migration terminée : {$affectedRows} paiements normalisés\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Pas de rollback car on ne peut pas savoir quelles valeurs étaient '01' ou '1' avant
        echo "Rollback non disponible pour cette migration\n";
    }
};
