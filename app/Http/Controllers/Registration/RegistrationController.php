<?php

namespace App\Http\Controllers\Registration;

use App\DTOs\Registration\CreateRegistrationDTO;
use App\DTOs\Registration\CreateStudentDTO;
use App\DTOs\Registration\RegistrationFilterDTO;
use App\DTOs\Registration\UpdateRegistrationDTO;
use App\Http\Controllers\Controller;
use App\Services\Registration\RegistrationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function __construct(
        private readonly RegistrationService $registrationService
    ) {}

    /**
     * Display a listing of registrations with filters and stats
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filter = RegistrationFilterDTO::fromArray($request->all());

            $result = $this->registrationService->getFilteredWithStats(
                $filter,
                $request->input('per_page', 15)
            );

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Register an existing student (ancien élève)
     */
    public function registerExistingStudent(\App\Http\Requests\Registration\RegisterExistingStudentRequest $request): JsonResponse
    {
        try {
            $dto = CreateRegistrationDTO::fromArray($request->validated());

            $registration = $this->registrationService->registerExistingStudent($dto);

            return response()->json([
                'success' => true,
                'message' => 'Inscription créée avec succès',
                'data' => $registration->load(['student', 'classRoom.option.section']),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Register a new student (nouvel élève)
     */
    public function registerNewStudent(\App\Http\Requests\Registration\RegisterNewStudentRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $studentDTO = CreateStudentDTO::fromArray($validated['student']);
            $registrationDTO = CreateRegistrationDTO::fromArray($validated['registration']);

            $registration = $this->registrationService->registerNewStudent(
                $studentDTO,
                $registrationDTO
            );

            return response()->json([
                'success' => true,
                'message' => 'Nouvel élève inscrit avec succès',
                'data' => $registration->load(['student', 'classRoom.option.section']),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified registration
     */
    public function show(int $id): JsonResponse
    {
        try {
            $registration = $this->registrationService->findById($id);

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Inscription non trouvée',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $registration,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified registration
     */
    public function update(\App\Http\Requests\Registration\UpdateRegistrationRequest $request, int $id): JsonResponse
    {
        try {
            $dto = UpdateRegistrationDTO::fromArray($request->validated());

            $registration = $this->registrationService->update($id, $dto);

            return response()->json([
                'success' => true,
                'message' => 'Inscription mise à jour avec succès',
                'data' => $registration->load(['student', 'classRoom.option.section']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Remove the specified registration
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->registrationService->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Inscription supprimée avec succès',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get statistics only
     */
    public function stats(Request $request): JsonResponse
    {
        try {
            $filter = RegistrationFilterDTO::fromArray($request->all());

            $stats = $this->registrationService->getStats($filter);

            return response()->json([
                'success' => true,
                'data' => $stats->toArray(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mark registration as abandoned
     */
    public function markAsAbandoned(int $id): JsonResponse
    {
        try {
            $registration = $this->registrationService->markAsAbandoned($id);

            return response()->json([
                'success' => true,
                'message' => 'Inscription marquée comme abandonnée',
                'data' => $registration,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Change student class
     */
    public function changeClass(Request $request, int $id): JsonResponse
    {
        try {
            $newClassRoomId = $request->input('class_room_id');

            $registration = $this->registrationService->changeClass($id, $newClassRoomId);

            return response()->json([
                'success' => true,
                'message' => 'Classe changée avec succès',
                'data' => $registration->load(['student', 'classRoom.option.section']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
