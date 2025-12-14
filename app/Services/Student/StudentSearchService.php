<?php

namespace App\Services\Student;

use App\Models\Registration;
use App\Models\SchoolYear;

class StudentSearchService
{
    /**
     * Rechercher des élèves par nom
     *
     * @param string $searchTerm
     * @param int|null $schoolYearId
     * @param int $limit
     * @return array
     */
    public function searchStudents(string $searchTerm, ?int $schoolYearId = null, int $limit = 20): array
    {
        $schoolYearId = $schoolYearId ?? SchoolYear::DEFAULT_SCHOOL_YEAR_ID();

        return Registration::with(['student', 'classRoom', 'classRoom.option'])
            ->whereHas('student', function ($query) use ($searchTerm) {
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('name', 'LIKE', '%' . strtoupper($searchTerm) . '%')
                        ->orWhere('name', 'LIKE', '%' . ucwords(strtolower($searchTerm)) . '%');
                });
            })
            ->where('school_year_id', $schoolYearId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($registration) {
                return [
                    'id' => $registration->id,
                    'student_name' => $registration->student->name,
                    'code' => $registration->code,
                    'class_room' => $registration->classRoom?->getOriginalClassRoomName(),
                    'option' => $registration->classRoom?->option?->name,
                ];
            })
            ->toArray();
    }

    /**
     * Récupérer les informations d'un élève
     *
     * @param int $registrationId
     * @return array|null
     */
    public function getStudentInfo(int $registrationId): ?array
    {
        $registration = Registration::with([
            'student',
            'classRoom.option.section',
            'classRoom'
        ])->find($registrationId);

        if (!$registration) {
            return null;
        }

        return [
            'registration' => $registration,
            'info' => [
                'name' => $registration->student->name,
                'code' => $registration->code,
                'class_room' => $registration->classRoom?->getOriginalClassRoomName(),
                'created_at' => $registration->created_at?->format('d/m/Y'),
            ]
        ];
    }
}
