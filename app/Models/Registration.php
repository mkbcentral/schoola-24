<?php

namespace App\Models;

use App\Domain\Features\Payment\PaymentFeature;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Registration extends Model
{
    use HasFactory;

    public mixed $isRegistered;
    protected $fillable = [
        'code',
        'registration_number',
        'student_id',
        'registration_fee_id',
        'class_room_id',
        'school_year_id',
        'rate_id',
        'qr_code',
        'is_registered',
        'is_old',
        'abandoned',
        'class_changed',
        'created_at',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_old' => 'boolean',
            'abandoned' => 'boolean',
            'class_changed' => 'boolean',
            'is_registered' => 'boolean',
        ];
    }

    /**
     * Get the student that owns the Registration
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Get the registrationFee that owns the Registration
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function registrationFee(): BelongsTo
    {
        return $this->belongsTo(RegistrationFee::class, 'registration_fee_id');
    }

    /**
     * Get the classRoom that owns the Registration
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classRoom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'class_room_id');
    }

    /**
     * Get the schoolYear that owns the Registration
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    /**
     * Get the rate that owns the Registration
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rate(): BelongsTo
    {
        return $this->belongsTo(Rate::class, 'rate_id');
    }

    /**
     * Get all of the payments for the Registration
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the changeClassStudent associated with the Registration
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function changeClassStudent(): HasOne
    {
        return $this->hasOne(ChangeClassStudent::class);
    }

    /**
     * Get the giveUoStudent associated with the Registration
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function giveUoStudent(): HasOne
    {
        return $this->hasOne(GiveUpStudent::class);
    }

    /**
     * Summary of scopeFilter
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    // Scope for filtering registrations with reusable joins and conditions
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $this->applyJoins($query)
            ->where('sections.school_id', School::DEFAULT_SCHOOL_ID())
            ->where('registrations.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->when($filters['date'] ?? null, fn($q, $date) => $q->whereDate('registrations.created_at', $date))
            ->when($filters['month'] ?? null, fn($q, $month) => $q->whereMonth('registrations.created_at', $month))
            ->when($filters['section_id'] ?? null, fn($q, $sectionId) => $q->where('sections.id', $sectionId))
            ->when($filters['option_id'] ?? null, fn($q, $optionId) => $q->where('options.id', $optionId))
            ->when($filters['class_room_id'] ?? null, fn($q, $classRoomId) => $q->where('class_rooms.id', $classRoomId))
            ->when($filters['responsible_student_id'] ?? null, fn($q, $responsibleStudentId) => $q->where('students.responsible_student_id', $responsibleStudentId))
            ->when(isset($filters['is_old']), fn($q) => $q->where('registrations.is_old', $filters['is_old']))
            ->when($filters['q'] ?? null, fn($q, $qVal) => $q->where('students.name', 'like', '%' . $qVal . '%'))
            ->with(['student', 'registrationFee', 'classRoom', 'schoolYear', 'payments', 'rate'])
            ->select('registrations.*', 'students.name');
    }

    // Centralized method for common joins
    protected function applyJoins(Builder $query): Builder
    {
        return $query
            ->join('students', 'students.id', '=', 'registrations.student_id')
            ->join('responsible_students', 'responsible_students.id', '=', 'students.responsible_student_id')
            ->join('class_rooms', 'class_rooms.id', '=', 'registrations.class_room_id')
            ->join('options', 'options.id', '=', 'class_rooms.option_id')
            ->join('sections', 'sections.id', '=', 'options.section_id');
    }


    /**
     * Compte le nombre d'inscriptions d'élèves groupées par un champ spécifique.
     *
     * Cette méthode effectue une requête sur la table des inscriptions, en joignant les tables
     * 'class_rooms', 'options' et 'sections' pour permettre le groupement par un champ donné.
     * Elle retourne un tableau associatif où la clé est la valeur du champ de groupement
     * (spécifié par $groupField et nommé $groupAlias) et la valeur est le nombre d'élèves
     * correspondants à ce groupe.
     *
     * @param string $groupField   Le nom du champ sur lequel effectuer le groupement (ex: 'sections.id').
     * @param string $groupAlias   L'alias à utiliser pour le champ de groupement dans le résultat.
     * @return array               Tableau associatif [valeur du groupe => nombre d'élèves].
     */
    protected static function countByGroup(string $groupField, string $groupAlias): array
    {
        return self::query()
            ->join('class_rooms', 'class_rooms.id', '=', 'registrations.class_room_id')
            ->join('options', 'options.id', '=', 'class_rooms.option_id')
            ->join('sections', 'sections.id', '=', 'options.section_id')
            ->selectRaw("$groupField as $groupAlias, COUNT(*) as student_count")
            ->where('registrations.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->groupBy($groupField)
            ->pluck('student_count', $groupAlias)
            ->toArray();
    }

    public static function countByGender(): array
    {
        $counts = self::whereHas('student', function ($query) {
            $query->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID());
        })
            ->with(['student' => function ($query) {
                $query->select('id', 'gender');
            }])
            ->get()
            ->groupBy(fn($registration) => $registration->student->gender ?? 'unknown')
            ->map(fn($group) => $group->count())
            ->toArray();

        return [
            'male' => $counts['M'] ?? 0,
            'female' => $counts['F'] ?? 0,
        ];
    }

    public static function countStudentByOption(): array
    {
        return self::countByGroup('options.name', 'option_name');
    }

    public static function countStudentBySection(): array
    {
        return self::countByGroup('sections.name', 'section_name');
    }

    public static function countStudentByClassRoom(int $optionId): array
    {
        return self::query()
            ->join('class_rooms', 'class_rooms.id', '=', 'registrations.class_room_id')
            ->join('options', 'options.id', '=', 'class_rooms.option_id')
            ->where('options.id', $optionId)
            ->selectRaw('CONCAT(class_rooms.name, " - ", options.name) as class_room_option_name, COUNT(*) as student_count')
            ->where('registrations.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->groupBy('class_room_option_name')
            ->pluck('student_count', 'class_room_option_name')
            ->toArray();
    }

    public function getStatusPayment(int $registrationId, $categoryFeeId, $schoolYearId, string $month): bool
    {
        $payment = PaymentFeature::getSinglePaymentForStudentWithMonth(
            $registrationId,
            $categoryFeeId,
            $schoolYearId,
            $month
        );
        return (bool) $payment;
    }

    public function getStatusPaymentByTranch(int $registrationId, $categoryFeeId, int $scolarFeeId): bool
    {
        $payment = PaymentFeature::getSinglePaymentForStudentWithTranche(
            $registrationId,
            $categoryFeeId,
            $scolarFeeId
        );
        return (bool) $payment;
    }
}
