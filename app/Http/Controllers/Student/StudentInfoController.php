<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\Student\StudentInfoService;
use Illuminate\Http\JsonResponse;

class StudentInfoController extends Controller
{
    protected StudentInfoService $studentInfoService;

    public function __construct(StudentInfoService $studentInfoService)
    {
        $this->studentInfoService = $studentInfoService;
    }

    /**
     * Récupère toutes les informations d'un élève
     */
    public function show(int $registrationId): JsonResponse
    {
        $info = $this->studentInfoService->getStudentCompleteInfo($registrationId);

        if (! $info) {
            return response()->json([
                'success' => false,
                'message' => 'Élève non trouvé',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $info,
        ]);
    }

    /**
     * Récupère l'historique des paiements
     */
    public function paymentHistory(int $registrationId): JsonResponse
    {
        $history = $this->studentInfoService->getPaymentHistory($registrationId);

        return response()->json([
            'success' => true,
            'data' => $history,
        ]);
    }

    /**
     * Récupère un résumé rapide
     */
    public function summary(int $registrationId): JsonResponse
    {
        $summary = $this->studentInfoService->getStudentSummary($registrationId);

        if (! $summary) {
            return response()->json([
                'success' => false,
                'message' => 'Élève non trouvé',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $summary,
        ]);
    }
}
