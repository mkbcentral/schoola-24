<?php

namespace App\Http\Controllers;

use App\Domain\Features\Configuration\SchoolDataFeature;
use App\Domain\Features\Registration\RegistrationFeature;
use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SchoolDataPrinterController extends Controller
{
    /**
     * Imprimer la liste des effectifs pour chaque classe par option
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function printStudentNumbersPerClassRoom(Request $request)
    {
        $options = SchoolDataFeature::getOptionList();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('prints.school.print-list-class-room-by-option', compact(['options']));
        return $pdf->stream();
    }

    public function printListStudeForClassRoom(
        Request $request,
        int $classRoomId,
        bool $sortAsc
    ): mixed {
        $sortBy = 'students.name';
        $classRoom = ClassRoom::find($classRoomId);
        $registrationns = RegistrationFeature::getList(
            null,
            null,
            null,
            null,
            $classRoomId,
            null,
            null,
            $sortBy,
            $sortAsc,
            100
        );
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView(
            'prints.school.print-list-students-by-class-room',
            compact(
                ['registrationns', 'classRoom']
            )
        );
        return $pdf->stream();
    }
}
