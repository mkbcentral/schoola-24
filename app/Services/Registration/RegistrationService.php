<?php

namespace App\Services\Registration;

use App\Actions\Registration\CreateNewStudentRegistrationAction;
use App\Actions\Registration\CreateRegistrationAction;
use App\Actions\Registration\DeleteRegistrationAction;
use App\Actions\Registration\UpdateRegistrationAction;
use App\DTOs\Registration\CreateRegistrationDTO;
use App\DTOs\Registration\CreateStudentDTO;
use App\DTOs\Registration\RegistrationFilterDTO;
use App\DTOs\Registration\RegistrationStatsDTO;
use App\DTOs\Registration\UpdateRegistrationDTO;
use App\Models\Registration;
use App\Models\SchoolYear;
use App\Repositories\Registration\RegistrationRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class RegistrationService
{
    public function __construct(
        private readonly RegistrationRepository $repository,
        private readonly CreateRegistrationAction $createRegistrationAction,
        private readonly CreateNewStudentRegistrationAction $createNewStudentRegistrationAction,
        private readonly UpdateRegistrationAction $updateRegistrationAction,
        private readonly DeleteRegistrationAction $deleteRegistrationAction,
    ) {}

    /**
     * Create registration for an existing student (ancien élève)
     */
    public function registerExistingStudent(CreateRegistrationDTO $dto): Registration
    {
        // Vérifier si l'étudiant n'est pas déjà inscrit pour cette année
        $schoolYearId = $dto->school_year_id ?? SchoolYear::DEFAULT_SCHOOL_YEAR_ID();

        if ($this->repository->isStudentRegistered($dto->student_id, $schoolYearId)) {
            throw new \Exception("Cet élève est déjà inscrit pour cette année scolaire.");
        }

        // Marquer comme ancien élève
        $data = $dto->toArray();
        $data['is_old'] = true;
        $data['school_year_id'] = $schoolYearId;

        $newDto = CreateRegistrationDTO::fromArray($data);

        return $this->createRegistrationAction->execute($newDto);
    }

    /**
     * Create registration for a new student (nouvel élève)
     */
    public function registerNewStudent(
        CreateStudentDTO $studentDTO,
        CreateRegistrationDTO $registrationDTO
    ): Registration {
        // Vérifier que school_year_id est défini
        $schoolYearId = $registrationDTO->school_year_id ?? SchoolYear::DEFAULT_SCHOOL_YEAR_ID();

        // Marquer comme nouvel élève
        $registrationData = $registrationDTO->toArray();
        $registrationData['is_old'] = false;
        $registrationData['school_year_id'] = $schoolYearId;

        $newRegistrationDTO = CreateRegistrationDTO::fromArray($registrationData);

        return $this->createNewStudentRegistrationAction->execute($studentDTO, $newRegistrationDTO);
    }

    /**
     * Update a registration
     */
    public function update(int $registrationId, UpdateRegistrationDTO $dto): Registration
    {
        $registration = $this->repository->findById($registrationId);

        if (!$registration) {
            throw new \Exception("Inscription non trouvée.");
        }

        return $this->updateRegistrationAction->execute($registration, $dto);
    }

    /**
     * Delete a registration
     */
    public function delete(int $registrationId): bool
    {
        $registration = $this->repository->findById($registrationId);

        if (!$registration) {
            throw new \Exception("Inscription non trouvée.");
        }

        return $this->deleteRegistrationAction->execute($registration);
    }

    /**
     * Get registration by ID
     */
    public function findById(int $id): ?Registration
    {
        return $this->repository->findById($id);
    }

    /**
     * Get filtered registrations with pagination
     */
    public function getFiltered(
        RegistrationFilterDTO $filter,
        int $perPage = 15,
        bool $paginate = true
    ): Collection|LengthAwarePaginator {
        return $this->repository->getFiltered($filter, $perPage, $paginate);
    }

    /**
     * Get registration statistics
     */
    public function getStats(RegistrationFilterDTO $filter): RegistrationStatsDTO
    {
        return $this->repository->getStats($filter);
    }

    /**
     * Get registrations with stats (combined result)
     */
    public function getFilteredWithStats(
        RegistrationFilterDTO $filter,
        int $perPage = 15
    ): array {
        $registrations = $this->repository->getFiltered($filter, $perPage, true);
        $stats = $this->repository->getStats($filter);

        return [
            'registrations' => $registrations,
            'stats' => $stats->toArray(),
        ];
    }

    /**
     * Get all registrations for a student
     */
    public function getByStudentId(int $studentId, ?int $schoolYearId = null): Collection
    {
        return $this->repository->findByStudentId($studentId, $schoolYearId);
    }

    /**
     * Check if student is registered for a school year
     */
    public function isStudentRegistered(int $studentId, ?int $schoolYearId = null): bool
    {
        $schoolYearId = $schoolYearId ?? SchoolYear::DEFAULT_SCHOOL_YEAR_ID();
        return $this->repository->isStudentRegistered($studentId, $schoolYearId);
    }

    /**
     * Get all registrations for current school year
     */
    public function getAllForCurrentSchoolYear(): Collection
    {
        $schoolYearId = SchoolYear::DEFAULT_SCHOOL_YEAR_ID();
        return $this->repository->getAllForSchoolYear($schoolYearId);
    }

    /**
     * Get count by class room for current school year
     */
    public function countByClassRoom(int $classRoomId, ?int $schoolYearId = null): int
    {
        $schoolYearId = $schoolYearId ?? SchoolYear::DEFAULT_SCHOOL_YEAR_ID();
        return $this->repository->countByClassRoom($classRoomId, $schoolYearId);
    }

    /**
     * Mark registration as abandoned
     */
    public function markAsAbandoned(int $registrationId): Registration
    {
        return $this->update(
            $registrationId,
            UpdateRegistrationDTO::fromArray(['abandoned' => true])
        );
    }

    /**
     * Mark registration as not abandoned
     */
    public function markAsNotAbandoned(int $registrationId): Registration
    {
        return $this->update(
            $registrationId,
            UpdateRegistrationDTO::fromArray(['abandoned' => false])
        );
    }

    /**
     * Mark registration fee as exempted
     */
    public function markFeeExempted(int $registrationId): Registration
    {
        return $this->update(
            $registrationId,
            UpdateRegistrationDTO::fromArray(['is_fee_exempted' => true])
        );
    }

    /**
     * Change student class
     */
    public function changeClass(int $registrationId, int $newClassRoomId): Registration
    {
        return $this->update(
            $registrationId,
            UpdateRegistrationDTO::fromArray([
                'class_room_id' => $newClassRoomId,
                'class_changed' => true,
            ])
        );
    }
}
