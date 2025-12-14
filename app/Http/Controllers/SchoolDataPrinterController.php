<?php

namespace App\Http\Controllers;

use App\Domain\Features\Configuration\SchoolDataFeature;
use App\Domain\Features\Registration\RegistrationFeature;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SchoolDataPrinterController extends Controller
{
    /**
     * Imprimer la liste des effectifs pour chaque classe par option
     *
     * @return mixed
     */
    public function printStudentNumbersPerClassRoom(Request $request)
    {
        $options = SchoolDataFeature::getOptionList();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('prints.school.print-list-class-room-by-option', compact(['options']));

        return $pdf->stream();
    }

    /**
     * Imprimer la liste des iffectifs par classe
     */
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

    /**
     * Imprimer la liste des iffectifs par classe
     */
    public function printListStudentCardsForClassRoom(
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
            'prints.school.print-list-students-cards-by-class-room',
            compact(
                ['registrationns', 'classRoom']
            )
        );

        return $pdf->stream();
    }

    /**
     * Imprimer la liste des élèves par date
     *
     * @param  mixed  $date
     */
    public function printListStudentByDate(
        ?string $date,
        bool $isOld,
        bool $sortAsc
    ): mixed {
        $sortBy = 'students.name';
        $registrationns = RegistrationFeature::getListOldOrNew(
            $date,
            null,
            null,
            null,
            null,
            null,
            $isOld,
            null,
            $sortBy,
            $sortAsc,
            1000
        );
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView(
            'prints.school.print-list-students-by-date-or-month',
            compact(
                ['registrationns', 'date', 'isOld']
            )
        );

        return $pdf->stream();
    }

    /**
     * Imprimer la liste de élèves par mois
     *
     * @param  mixed  $month
     */
    public function printListStudentByMonth(
        ?string $month,
        bool $isOld,
        bool $sortAsc
    ): mixed {
        $sortBy = 'students.name';
        $registrationns = RegistrationFeature::getListOldOrNew(
            null,
            $month,
            null,
            null,
            null,
            null,
            $isOld,
            null,
            $sortBy,
            $sortAsc,
            1000
        );
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView(
            'prints.school.print-list-students-by-date-or-month',
            compact(
                ['registrationns']
            )
        );

        return $pdf->stream();
    }
}
