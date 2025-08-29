<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Features\Registration\RegistrationFeature;
use App\Domain\Helpers\DateFormatHelper;
use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Option;
use App\Models\Registration;
use Illuminate\Http\Request;

class StudentPrinterController extends Controller
{
    public function printStudentPayments(Registration $registration)
    {
        $months = DateFormatHelper::getSchoolFrMonths();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('prints.student.student-payments', compact(['registration', 'months']));
        return $pdf->stream();
    }

    public function printStudentPaymentsByClassRoom(ClassRoom $classRoom)
    {
        set_time_limit(300);
        $registrations = RegistrationFeature::getList(
            null,
            null,
            null,
            null,
            $classRoom->id,
            null,
            null,
            'name',
            true,
            null,
            1000
        );
        $months = DateFormatHelper::getSchoolFrMonths();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('prints.student.student-payments-by-classroom', compact(['registrations', 'months']));
        return $pdf->stream();
    }

    public function printAllStudentList(int $optionId = 0, int $classRoomId = 0)
    {
        $registrations = RegistrationFeature::getList(
            null,
            null,
            null,
            $optionId,
            $classRoomId,
            null,
            null,
            'name',
            true,
            null,
            1000
        );
        $option = Option::find($optionId);
        $classRoom = ClassRoom::find($classRoomId);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('prints.student.all-student-list', compact(['registrations', 'option', 'classRoom']));
        return $pdf->stream();
    }
}
