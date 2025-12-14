<?php

namespace Tests\Unit;

use App\Models\ClassRoom;
use App\Models\Option;
use App\Models\Payment;
use App\Models\Rate;
use App\Models\Registration;
use App\Models\ResponsibleStudent;
use App\Models\School;
use App\Models\SchoolYear;
use App\Models\ScolarFee;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    private School $school;

    private SchoolYear $schoolYear;

    private User $user;

    private Student $student;

    private Registration $registration;

    private ScolarFee $scolarFee;

    private Rate $rate;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer les données de test nécessaires
        $this->school = School::factory()->create();
        $this->schoolYear = SchoolYear::factory()->create([
            'school_id' => $this->school->id,
            'is_last_year' => true,
        ]);

        $this->user = User::factory()->create([
            'school_id' => $this->school->id,
        ]);

        $section = Section::factory()->create(['school_id' => $this->school->id]);
        $option = Option::factory()->create(['section_id' => $section->id]);
        $classRoom = ClassRoom::factory()->create(['option_id' => $option->id]);

        $responsible = ResponsibleStudent::factory()->create([
            'school_id' => $this->school->id,
        ]);

        $this->student = Student::factory()->create([
            'responsible_student_id' => $responsible->id,
        ]);

        $this->registration = Registration::factory()->create([
            'student_id' => $this->student->id,
            'school_year_id' => $this->schoolYear->id,
            'class_room_id' => $classRoom->id,
        ]);

        $this->scolarFee = ScolarFee::factory()->create();
        $this->rate = Rate::factory()->create(['school_id' => $this->school->id]);
    }

    public function test_payment_can_be_created(): void
    {
        $payment = Payment::create([
            'payment_number' => 'PAY-001',
            'month' => 'JANVIER',
            'registration_id' => $this->registration->id,
            'scolar_fee_id' => $this->scolarFee->id,
            'rate_id' => $this->rate->id,
            'user_id' => $this->user->id,
            'is_paid' => true,
        ]);

        $this->assertDatabaseHas('payments', [
            'payment_number' => 'PAY-001',
            'month' => 'JANVIER',
            'is_paid' => true,
        ]);

        $this->assertTrue($payment->is_paid);
        $this->assertEquals('PAY-001', $payment->payment_number);
    }

    public function test_payment_belongs_to_registration(): void
    {
        $payment = Payment::factory()->create([
            'registration_id' => $this->registration->id,
            'scolar_fee_id' => $this->scolarFee->id,
            'rate_id' => $this->rate->id,
            'user_id' => $this->user->id,
        ]);

        $this->assertInstanceOf(Registration::class, $payment->registration);
        $this->assertEquals($this->registration->id, $payment->registration->id);
    }

    public function test_payment_belongs_to_scolar_fee(): void
    {
        $payment = Payment::factory()->create([
            'registration_id' => $this->registration->id,
            'scolar_fee_id' => $this->scolarFee->id,
            'rate_id' => $this->rate->id,
            'user_id' => $this->user->id,
        ]);

        $this->assertInstanceOf(ScolarFee::class, $payment->scolarFee);
        $this->assertEquals($this->scolarFee->id, $payment->scolarFee->id);
    }

    public function test_payment_belongs_to_user(): void
    {
        $payment = Payment::factory()->create([
            'registration_id' => $this->registration->id,
            'scolar_fee_id' => $this->scolarFee->id,
            'rate_id' => $this->rate->id,
            'user_id' => $this->user->id,
        ]);

        $this->assertInstanceOf(User::class, $payment->user);
        $this->assertEquals($this->user->id, $payment->user->id);
    }

    public function test_payment_is_paid_default_is_false(): void
    {
        $payment = Payment::factory()->create([
            'registration_id' => $this->registration->id,
            'scolar_fee_id' => $this->scolarFee->id,
            'rate_id' => $this->rate->id,
            'user_id' => $this->user->id,
            'is_paid' => false,
        ]);

        $this->assertFalse($payment->is_paid);
    }

    public function test_payment_fillable_attributes_work(): void
    {
        $data = [
            'payment_number' => 'PAY-TEST-001',
            'month' => 'FEVRIER',
            'registration_id' => $this->registration->id,
            'scolar_fee_id' => $this->scolarFee->id,
            'rate_id' => $this->rate->id,
            'user_id' => $this->user->id,
            'is_paid' => true,
        ];

        $payment = Payment::create($data);

        $this->assertEquals('PAY-TEST-001', $payment->payment_number);
        $this->assertEquals('FEVRIER', $payment->month);
        $this->assertTrue($payment->is_paid);
    }

    public function test_get_amount_returns_scolar_fee_amount(): void
    {
        $payment = Payment::factory()->create([
            'registration_id' => $this->registration->id,
            'scolar_fee_id' => $this->scolarFee->id,
            'rate_id' => $this->rate->id,
            'user_id' => $this->user->id,
        ]);

        $amount = $payment->getAmount();

        $this->assertEquals($this->scolarFee->amount, $amount);
    }
}
