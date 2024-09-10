<?php

namespace App\Domain\Features\Student;

use App\Domain\Contract\Student\IStudent;
use App\Models\Registration;
use App\Models\School;
use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;
use LaravelQRCode\Facades\QRCode;

/**
 * Summary of StudentFeature
 */
class StudentFeature implements IStudent
{
    private static string $keyToSearch;
    /**
     * @inheritDoc
     */
    public static function create(array $input): Student
    {
        return Student::create($input);
    }

    /**
     * @inheritDoc
     */
    public static function delete(Student $student): bool
    {
        return $student->delete();
    }

    /**
     * @inheritDoc
     */
    public static function get(int $id): Student
    {
        return Student::find($id);
    }

    /**
     * @inheritDoc
     */
    public static function update(Student $student, array $input): bool
    {
        return $student->update($input);
    }
    /**
     * @inheritDoc
     */
    /**
     * @inheritDoc
     */
    public static function getListByResponsibleStudent(
        int $id,
        string $q,
        string $sortBy,
        bool $sortAsc,
        int $per_page = 50
    ): mixed {
        SELF::$keyToSearch = $q;
        return Student::query()
            ->join('responsible_students', 'students.responsible_student_id', 'responsible_students.id')
            ->where("students.responsible_student_id", $id)
            ->when($q, function ($query) {
                return $query->where(function ($query) {
                    return $query->where('students.name', 'like', '%' . SELF::$keyToSearch . '%')
                        ->orWhere('students.place_of_birth', 'like', '%' . SELF::$keyToSearch . '%')
                        ->orWhere('students.date_of_birth', 'like', '%' . SELF::$keyToSearch . '%');
                });
            })
            ->where('responsible_students.school_id', School::DEFAULT_SCHOOL_ID())
            ->orderBy($sortBy, $sortAsc ? 'ASC' : 'DESC')
            ->with([
                'responsibleStudent',
            ])
            ->paginate($per_page);
    }
    /**
     * @inheritDoc
     */
    public static function getList(
        string $q,
        string $sortBy,
        bool $sortAsc,
        int $per_page = 20,
        int $option_filer
    ): mixed {
        SELF::$keyToSearch = $q;
        return Student::query()
            ->join('responsible_students', 'students.responsible_student_id', 'responsible_students.id')
            ->join('registrations', 'registrations.student_id', 'students.id')
            ->join('class_rooms', 'registrations.class_room_id', 'class_rooms.id')
            ->when($q, function ($query) {
                return $query->where(function ($query) {
                    return $query->where('students.name', 'like', '%' . SELF::$keyToSearch . '%')
                        ->orWhere('students.place_of_birth', 'like', '%' . SELF::$keyToSearch . '%')
                        ->orWhere('students.date_of_birth', 'like', '%' . SELF::$keyToSearch . '%');
                });
            })
            ->when(
                $option_filer,
                function ($query, $f) {
                    return $query->where('class_rooms.option_id', $f);
                }
            )
            ->where('responsible_students.school_id', School::DEFAULT_SCHOOL_ID())
            ->where('registrations.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->select('students.*')
            ->orderBy($sortBy, $sortAsc ? 'ASC' : 'DESC')
            ->with([
                'responsibleStudent',
                'registration.classRoom'
            ])
            ->paginate($per_page);
    }
    /**
     * @inheritDoc
     */
    public static function generateStudentQRCode(Registration $registration): string
    {
        $filename = $registration->student->name . '-qr-code.png';
        $directory = 'school/qrcodes';

        // Créer le répertoire s'il n'existe pas
        if (!Storage::exists('public/' . $directory)) {
            Storage::makeDirectory('public/' . $directory);
        }

        // Chemin complet où le QR code sera sauvegardé
        $path = $directory . '/' . $filename;

        // Générer et stocker le QR code
        QRCode::text($registration->code)
            ->setOutfile(storage_path('app/public/' . $path))
            ->png();
        // Retourner le chemin relatif
        $relativePath = '/storage/' . $path;
        return $relativePath;
    }
}
