<?php

namespace App\Services;

use App\Models\RegistrationDerogation;
use App\Models\Registration;
use Carbon\Carbon;

class DerogationTrackerService
{
    /**
     * Vérifie toutes les dérogations expirées et désactive l'état de dérogation sur l'inscription.
     */
    public function checkAndDisableExpiredDerogations(): void
    {
        $today = Carbon::today();
        $expired = RegistrationDerogation::where('end_date', '<', $today)->get();
        foreach ($expired as $derogation) {
            $registration = $derogation->registration;
            if ($registration && $registration->is_under_derogation) {
                $registration->is_under_derogation = false;
                $registration->save();
            }
        }
    }
}
