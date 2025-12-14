<?php

namespace Tests\Unit;

use App\Models\ClassRoom;
use App\Models\Option;
use App\Models\Registration;
use App\Models\ResponsibleStudent;
use App\Models\School;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    private School $school;

    private SchoolYear $schoolYear;

    private Student $student;

    private ClassRoom $classRoom;

    protected function setUp(): void
    {
        parent::setUp();

        $this->school = School::factory()->create();
        $this->schoolYear = SchoolYear::factory()->create([
            'school_id' => $this->school->id,
        ]);

        $responsible = ResponsibleStudent::factory()->create([
            'school_id' => $this->school->id,
        ]);

        $this->student = Student::factory()->create([
            'responsible_student_id' => $responsible->id,
        ]);

        $section = Section::factory()->create(['school_id' => $this->school->id]);
        $option = Option::factory()->create(['section_id' => $section->id]);
        $this->classRoom = ClassRoom::factory()->create(['option_id' => $option->id]);
    }

    public function test_registration_can_be_created(): void
    {
        $registration = Registration::create([
            'code' => 'REG-2024-001',
            'student_id' => $this->student->id,
            'school_year_id' => $this->schoolYear->id,
            'class_room_id' => $this->classRoom->id,
        ]);

        $this->assertDatabaseHas('registrations', [
            'code' => 'REG-2024-001',
            'student_id' => $this->student->id,
        ]);
    }

    public function test_registration_belongs_to_student(): void
    {
        $registration = Registration::factory()->create([
            'student_id' => $this->student->id,
            'school_year_id' => $this->schoolYear->id,
            'class_room_id' => $this->classRoom->id,
        ]);

        $this->assertInstanceOf(Student::class, $registration->student);
        $this->assertEquals($this->student->id, $registration->student->id);
    }

    public function test_registration_belongs_to_school_year(): void
    {
        $registration = Registration::factory()->create([
            'student_id' => $this->student->id,
            'school_year_id' => $this->schoolYear->id,
            'class_room_id' => $this->classRoom->id,
        ]);

        $this->assertInstanceOf(SchoolYear::class, $registration->schoolYear);
        $this->assertEquals($this->schoolYear->id, $registration->schoolYear->id);
    }

    public function test_registration_belongs_to_class_room(): void
    {
        $registration = Registration::factory()->create([
            'student_id' => $this->student->id,
            'school_year_id' => $this->schoolYear->id,
            'class_room_id' => $this->classRoom->id,
        ]);

        $this->assertInstanceOf(ClassRoom::class, $registration->classRoom);
        $this->assertEquals($this->classRoom->id, $registration->classRoom->id);
    }

    public function test_registration_is_fee_exempted_default_is_false(): void
    {
        $registration = Registration::factory()->create([
            'student_id' => $this->student->id,
            'school_year_id' => $this->schoolYear->id,
            'class_room_id' => $this->classRoom->id,
            'is_fee_exempted' => false,
        ]);

        $this->assertFalse($registration->is_fee_exempted);
    }

    public function test_registration_unique_code_can_be_generated(): void
    {
        $registration = Registration::factory()->create([
            'student_id' => $this->student->id,
            'school_year_id' => $this->schoolYear->id,
            'class_room_id' => $this->classRoom->id,
        ]);

        $this->assertNotEmpty($registration->code);
    }
}
