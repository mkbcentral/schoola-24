<?php

namespace App\Http\Controllers;

use App;
use App\Domain\Features\Registration\RegistrationFeature;
use App\Domain\Helpers\DateFormatHelper;
use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
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
            1000
        );
        $months = DateFormatHelper::getSchoolFrMonths();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('prints.student.student-payments-by-classroom', compact(['registrations', 'months']));
        return $pdf->stream();
    }
}
