<?php

namespace App\Repositories\Registration;

use App\DTOs\Registration\RegistrationFilterDTO;
use App\DTOs\Registration\RegistrationStatsDTO;
use App\Models\ClassRoom;
use App\Models\Option;
use App\Models\Registration;
use App\Models\SchoolYear;
use App\Models\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class RegistrationRepository
{
    /**
     * Get registrations with filters
     */
    public function getFiltered(
        RegistrationFilterDTO $filter,
        int $perPage = 15,
        bool $paginate = true
    ): Collection|LengthAwarePaginator {
        $query = $this->buildFilteredQuery($filter);

        // Charger les relations
        $query->with([
            'student',
            'classRoom.option.section',
            'schoolYear',
            'registrationFee',
        ]);

        // Trier par date de création (plus récent en premier)
        $query->orderBy('created_at', 'desc');

        return $paginate ? $query->paginate($perPage) : $query->get();
    }

    /**
     * Get registration statistics based on filters
     */
    public function getStats(RegistrationFilterDTO $filter): RegistrationStatsDTO
    {
        $query = $this->buildFilteredQuery($filter);

        // Total global
        $total = $query->count();

        // Total par genre
        $totalMale = (clone $query)->whereHas('student', function (Builder $q) {
            $q->where('gender', 'M');
        })->count();

        $totalFemale = (clone $query)->whereHas('student', function (Builder $q) {
            $q->where('gender', 'F');
        })->count();

        // Total par section
        $bySection = $this->getCountBySection($filter);

        // Total par option
        $byOption = $this->getCountByOption($filter);

        // Total par classe
        $byClass = $this->getCountByClass($filter);

        return new RegistrationStatsDTO(
            total: $total,
            total_male: $totalMale,
            total_female: $totalFemale,
            by_section: $bySection,
            by_option: $byOption,
            by_class: $byClass,
        );
    }

    /**
     * Find registration by ID
     */
    public function findById(int $id): ?Registration
    {
        return Registration::with([
            'student.responsibleStudent',
            'classRoom.option.section',
            'schoolYear',
            'registrationFee',
        ])->find($id);
    }

    /**
     * Find registrations by student ID
     */
    public function findByStudentId(int $studentId, ?int $schoolYearId = null): Collection
    {
        $query = Registration::query()
            ->where('student_id', $studentId)
            ->with(['classRoom.option.section', 'schoolYear']);

        if ($schoolYearId) {
            $query->where('school_year_id', $schoolYearId);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Check if student is already registered for the school year
     */
    public function isStudentRegistered(int $studentId, int $schoolYearId): bool
    {
        return Registration::query()
            ->where('student_id', $studentId)
            ->where('school_year_id', $schoolYearId)
            ->exists();
    }

    /**
     * Get count by section
     */
    private function getCountBySection(RegistrationFilterDTO $filter): array
    {
        $sections = Section::with(['options.classRooms'])->get();
        $result = [];

        foreach ($sections as $section) {
            $sectionFilter = new RegistrationFilterDTO(
                school_year_id: $filter->school_year_id,
                section_id: $section->id,
                gender: $filter->gender,
                date_from: $filter->date_from,
                date_to: $filter->date_to,
                is_old: $filter->is_old,
                abandoned: $filter->abandoned,
                is_registered: $filter->is_registered,
            );

            $count = $this->buildFilteredQuery($sectionFilter)->count();

            if ($count > 0) {
                $result[] = [
                    'id' => $section->id,
                    'name' => $section->name,
                    'count' => $count,
                ];
            }
        }

        return $result;
    }

    /**
     * Get count by option
     */
    private function getCountByOption(RegistrationFilterDTO $filter): array
    {
        $query = Option::query();

        if ($filter->section_id) {
            $query->where('section_id', $filter->section_id);
        }

        $options = $query->with('classRooms')->get();
        $result = [];

        foreach ($options as $option) {
            $optionFilter = new RegistrationFilterDTO(
                school_year_id: $filter->school_year_id,
                section_id: $filter->section_id,
                option_id: $option->id,
                gender: $filter->gender,
                date_from: $filter->date_from,
                date_to: $filter->date_to,
                is_old: $filter->is_old,
                abandoned: $filter->abandoned,
                is_registered: $filter->is_registered,
            );

            $count = $this->buildFilteredQuery($optionFilter)->count();

            if ($count > 0) {
                $result[] = [
                    'id' => $option->id,
                    'name' => $option->name,
                    'section_name' => $option->section->name,
                    'count' => $count,
                ];
            }
        }

        return $result;
    }

    /**
     * Get count by class
     */
    private function getCountByClass(RegistrationFilterDTO $filter): array
    {
        $query = ClassRoom::query();

        if ($filter->option_id) {
            $query->where('option_id', $filter->option_id);
        } elseif ($filter->section_id) {
            $query->whereHas('option', function (Builder $q) use ($filter) {
                $q->where('section_id', $filter->section_id);
            });
        }

        $classRooms = $query->with('option.section')->get();
        $result = [];

        foreach ($classRooms as $classRoom) {
            $classFilter = new RegistrationFilterDTO(
                school_year_id: $filter->school_year_id,
                section_id: $filter->section_id,
                option_id: $filter->option_id,
                class_room_id: $classRoom->id,
                gender: $filter->gender,
                date_from: $filter->date_from,
                date_to: $filter->date_to,
                is_old: $filter->is_old,
                abandoned: $filter->abandoned,
                is_registered: $filter->is_registered,
            );

            $count = $this->buildFilteredQuery($classFilter)->count();

            if ($count > 0) {
                $result[] = [
                    'id' => $classRoom->id,
                    'name' => $classRoom->name,
                    'option_name' => $classRoom->option->name,
                    'section_name' => $classRoom->option->section->name,
                    'count' => $count,
                ];
            }
        }

        return $result;
    }

    /**
     * Build filtered query based on RegistrationFilterDTO
     */
    private function buildFilteredQuery(RegistrationFilterDTO $filter): Builder
    {
        $query = Registration::query();

        // Filtrer par année scolaire (utiliser l'année par défaut si non spécifiée)
        $schoolYearId = $filter->school_year_id ?? SchoolYear::DEFAULT_SCHOOL_YEAR_ID();
        $query->where('school_year_id', $schoolYearId);

        // Filtrer par classe
        if ($filter->class_room_id) {
            $query->where('class_room_id', $filter->class_room_id);
        }

        // Filtrer par option
        if ($filter->option_id) {
            $query->whereHas('classRoom', function (Builder $q) use ($filter) {
                $q->where('option_id', $filter->option_id);
            });
        }

        // Filtrer par section
        if ($filter->section_id) {
            $query->whereHas('classRoom.option', function (Builder $q) use ($filter) {
                $q->where('section_id', $filter->section_id);
            });
        }

        // Filtrer par genre
        if ($filter->gender) {
            $query->whereHas('student', function (Builder $q) use ($filter) {
                $q->where('gender', $filter->gender);
            });
        }

        // Filtrer par date d'inscription
        if ($filter->date_from) {
            $query->whereDate('created_at', '>=', $filter->date_from);
        }

        if ($filter->date_to) {
            $query->whereDate('created_at', '<=', $filter->date_to);
        }

        // Filtrer par ancien élève
        if ($filter->is_old !== null) {
            $query->where('is_old', $filter->is_old);
        }

        // Filtrer par élève abandonné
        if ($filter->abandoned !== null) {
            $query->where('abandoned', $filter->abandoned);
        }

        // Filtrer par inscription confirmée
        if ($filter->is_registered !== null) {
            $query->where('is_registered', $filter->is_registered);
        }

        return $query;
    }

    /**
     * Get all registrations for a specific school year
     */
    public function getAllForSchoolYear(int $schoolYearId): Collection
    {
        return Registration::query()
            ->where('school_year_id', $schoolYearId)
            ->with([
                'student',
                'classRoom.option.section',
            ])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Count registrations for a class room in a school year
     */
    public function countByClassRoom(int $classRoomId, int $schoolYearId): int
    {
        return Registration::query()
            ->where('class_room_id', $classRoomId)
            ->where('school_year_id', $schoolYearId)
            ->count();
    }
}
