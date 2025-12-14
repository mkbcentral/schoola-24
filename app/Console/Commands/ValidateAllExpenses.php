<?php

namespace App\Console\Commands;

use App\Models\ExpenseFee;
use App\Models\OtherExpense;
use Illuminate\Console\Command;

class ValidateAllExpenses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expenses:validate-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Marquer toutes les dépenses existantes comme validées (is_validated = true)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Début de la validation de toutes les dépenses...');
        $this->newLine();

        // Mettre à jour les expense_fees
        $this->info('📋 Mise à jour des Dépenses sur Frais (expense_fees)...');
        $expenseFeesCount = ExpenseFee::where('is_validated', false)->count();

        if ($expenseFeesCount > 0) {
            ExpenseFee::where('is_validated', false)->update(['is_validated' => true]);
            $this->line("   ✅ {$expenseFeesCount} dépense(s) sur frais validée(s)");
        } else {
            $this->line("   ℹ️  Aucune dépense sur frais à valider");
        }

        $this->newLine();

        // Mettre à jour les other_expenses
        $this->info('📋 Mise à jour des Autres Dépenses (other_expenses)...');
        $otherExpensesCount = OtherExpense::where('is_validated', false)->count();

        if ($otherExpensesCount > 0) {
            OtherExpense::where('is_validated', false)->update(['is_validated' => true]);
            $this->line("   ✅ {$otherExpensesCount} autre(s) dépense(s) validée(s)");
        } else {
            $this->line("   ℹ️  Aucune autre dépense à valider");
        }

        $this->newLine();

        // Résumé
        $totalValidated = $expenseFeesCount + $otherExpensesCount;

        if ($totalValidated > 0) {
            $this->info("✨ Succès ! {$totalValidated} dépense(s) au total ont été validées.");
        } else {
            $this->info("✨ Toutes les dépenses sont déjà validées !");
        }

        return Command::SUCCESS;
    }
}
