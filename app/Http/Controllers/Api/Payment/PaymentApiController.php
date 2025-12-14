<?php

namespace App\Http\Controllers\Api\Payment;

use App\DTOs\Payment\PaymentFilterDTO;
use App\Http\Controllers\Controller;
use App\Services\Contracts\PaymentServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Exemple de contrôleur utilisant PaymentService
 */
class PaymentApiController extends Controller
{
    public function __construct(
        private PaymentServiceInterface $paymentService
    ) {}

    /**
     * Liste des paiements avec filtres
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Créer le DTO à partir de la requête
        $filters = PaymentFilterDTO::fromArray($request->all());

        // Récupérer les résultats
        $result = $this->paymentService->getFilteredPayments(
            filters: $filters,
            perPage: $request->integer('per_page', 15)
        );

        return response()->json([
            'success' => true,
            'data' => [
                'payments' => $result->payments->items(),
                'pagination' => [
                    'total' => $result->payments->total(),
                    'per_page' => $result->payments->perPage(),
                    'current_page' => $result->payments->currentPage(),
                    'last_page' => $result->payments->lastPage(),
                ],
                'summary' => [
                    'total_count' => $result->totalCount,
                    'totals_by_currency' => $result->totalsByCurrency,
                    'statistics' => $result->statistics,
                ],
            ],
        ]);
    }

    /**
     * Afficher un paiement
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $payment = $this->paymentService->find($id);

        if (! $payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $payment,
        ]);
    }

    /**
     * Créer un nouveau paiement
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'registration_id' => 'required|integer|exists:registrations,id',
            'scolar_fee_id' => 'required|integer|exists:scolar_fees,id',
            'month' => 'nullable|string',
            'rate_id' => 'nullable|integer|exists:rates,id',
            'is_paid' => 'nullable|boolean',
        ]);

        $payment = $this->paymentService->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Payment created successfully',
            'data' => $payment,
        ], 201);
    }

    /**
     * Mettre à jour un paiement
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'month' => 'nullable|string',
            'is_paid' => 'nullable|boolean',
            'rate_id' => 'nullable|integer|exists:rates,id',
        ]);

        $updated = $this->paymentService->update($id, $validated);

        if (! $updated) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found or update failed',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment updated successfully',
        ]);
    }

    /**
     * Supprimer un paiement
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->paymentService->delete($id);

        if (! $deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found or deletion failed',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment deleted successfully',
        ]);
    }

    /**
     * Récupérer uniquement les statistiques (sans liste)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function statistics(Request $request): JsonResponse
    {
        $filters = PaymentFilterDTO::fromArray($request->all());

        // Utiliser perPage = 1 pour optimiser (on ne veut que les stats)
        $result = $this->paymentService->getFilteredPayments($filters, perPage: 1);

        return response()->json([
            'success' => true,
            'data' => [
                'total_count' => $result->totalCount,
                'totals_by_currency' => $result->totalsByCurrency,
                'statistics' => $result->statistics,
            ],
        ]);
    }
}
