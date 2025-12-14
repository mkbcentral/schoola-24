<?php

namespace Tests\Unit;

use App\Models\ResponsibleStudent;
use App\Models\School;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    private ResponsibleStudent $responsible;

    protected function setUp(): void
    {
        parent::setUp();

        $school = School::factory()->create();
        $this->responsible = ResponsibleStudent::factory()->create([
            'school_id' => $school->id,
        ]);
    }

    public function test_student_can_be_created(): void
    {
        $student = Student::create([
            'name' => 'MUKENDI Jean',
            'gender' => 'M',
            'place_of_birth' => 'Kinshasa',
            'date_of_birth' => '2010-01-15',
            'responsible_student_id' => $this->responsible->id,
        ]);

        $this->assertDatabaseHas('students', [
            'name' => 'MUKENDI Jean',
            'gender' => 'M',
            'place_of_birth' => 'Kinshasa',
        ]);
    }

    public function test_student_belongs_to_responsible(): void
    {
        $student = Student::factory()->create([
            'responsible_student_id' => $this->responsible->id,
        ]);

        $this->assertInstanceOf(ResponsibleStudent::class, $student->responsibleStudent);
        $this->assertEquals($this->responsible->id, $student->responsibleStudent->id);
    }

    public function test_student_has_many_registrations(): void
    {
        $student = Student::factory()->create([
            'responsible_student_id' => $this->responsible->id,
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $student->registrations);
    }

    public function test_student_age_is_calculated_correctly(): void
    {
        $birthDate = Carbon::now()->subYears(15);

        $student = Student::factory()->create([
            'responsible_student_id' => $this->responsible->id,
            'date_of_birth' => $birthDate,
        ]);

        $this->assertEquals(15, $student->age);
    }

    public function test_formatted_age_for_single_year(): void
    {
        $birthDate = Carbon::now()->subYear();

        $student = Student::factory()->create([
            'responsible_student_id' => $this->responsible->id,
            'date_of_birth' => $birthDate,
        ]);

        $this->assertEquals('1 An', $student->getFormattedAg());
    }

    public function test_formatted_age_for_multiple_years(): void
    {
        $birthDate = Carbon::now()->subYears(12);

        $student = Student::factory()->create([
            'responsible_student_id' => $this->responsible->id,
            'date_of_birth' => $birthDate,
        ]);

        $this->assertEquals('12 Ans', $student->getFormattedAg());
    }

    public function test_fillable_attributes_work(): void
    {
        $data = [
            'name' => 'KABULO Marie',
            'gender' => 'F',
            'place_of_birth' => 'Lubumbashi',
            'date_of_birth' => '2011-05-20',
            'responsible_student_id' => $this->responsible->id,
        ];

        $student = Student::create($data);

        $this->assertEquals('KABULO Marie', $student->name);
        $this->assertEquals('F', $student->gender);
        $this->assertEquals('Lubumbashi', $student->place_of_birth);
    }
}
