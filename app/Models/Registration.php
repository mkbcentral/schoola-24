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
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $this->reusableFilterQuery($query, $filters)
            ->when($filters['q'], function ($query, $q) {
                return $query->where('students.name', 'like', '%' . $q . '%');
            })
            ->select('registrations.*', 'students.name')
            ->orderBy($filters['sort_by'], $filters['sort_asc'] ? 'ASC' : 'DESC');
    }
    /**
     * Summary of scopeFilterOldOrnew
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterOldOrnew(Builder $query, array $filters): Builder
    {
        return $this->reusableFilterQuery($query, $filters)
            ->where('registrations.is_old', $filters['is_old'])
            ->select('registrations.*', 'students.name')
            ->orderBy($filters['sort_by'], $filters['sort_asc'] ? 'ASC' : 'DESC');
    }

    /**
     * Summary of scopeFilter
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterNotSorted(Builder $query, array $filters): Builder
    {
        return  $this->reusableFilterQuery($query, $filters)
            ->where('registrations.is_old', $filters['is_old']);
    }

    public function scopeFilterCounterAll(Builder $query, array $filters): Builder
    {
        return $this->reusableFilterQuery($query, $filters);
    }

    public function reusableFilterQuery(Builder $query, array $filters): Builder
    {
        return $query
            ->join('students', 'students.id', 'registrations.student_id')
            ->join('responsible_students', 'responsible_students.id', 'students.responsible_student_id')
            ->join("class_rooms", "class_rooms.id", "=", "registrations.class_room_id")
            ->join("options", "options.id", "=", "class_rooms.option_id")
            ->join("sections", "sections.id", "=", "options.section_id")
            ->where('sections.school_id', School::DEFAULT_SCHOOL_ID())
            ->where('registrations.school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->when($filters['date'], function ($query, $date) {
                return $query->whereDate('registrations.created_at', $date);
            })
            ->when($filters['month'], function ($query, $month) {
                return $query->whereMonth('registrations.created_at', $month);
            })
            ->when($filters['section_id'], function ($query, $sectionId) {
                return $query->where('sections.id', $sectionId);
            })
            ->when($filters['option_id'], function ($query, $optionId) {
                return $query->where('options.id', $optionId);
            })
            ->when($filters['class_room_id'], function ($query, $classRoomId) {
                return $query->where('class_rooms.id', $classRoomId);
            })
            ->when($filters['responsible_student_id'], function ($query, $classRoomId) {
                return $query->where('students.responsible_student_id', $classRoomId);
            })->with(
                [
                    'student',
                    'registrationFee',
                    'classRoom',
                    'schoolYear',
                    'payments',
                    'rate'
                ]
            );
    }

    public function getStatusPayment(int $registrationId, $categoryFeeId, string $month): bool
    {
        $statuse = false;
        $payment = PaymentFeature::getSinglePaymentForStudentWithMonth($registrationId, $categoryFeeId, $month);
        if ($payment) {
            $statuse = true;
        } else {
            $statuse = false;
        }
        return $statuse;
    }

    public function getStatusPaymentByTranch(int $registrationId, $categoryFeeId, int $scolarFeeId): bool
    {
        $statuse = false;
        $payment = PaymentFeature::getSinglePaymentForStudentWithTranche(
            $registrationId,
            $categoryFeeId,
            $scolarFeeId
        );
        if ($payment) {
            $statuse = true;
        } else {
            $statuse = false;
        }

        return $statuse;
    }
}
