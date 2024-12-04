<?php

namespace App\Http\Controllers;

use App\Domain\Features\Payment\PaymentFeature;
use App\Http\Controllers\Controller;
use App\Models\CategoryFee;
use App\Models\ClassRoom;
use App\Models\Option;
use App\Models\School;
use App\Models\SchoolYear;
use App\Models\Section;
use Illuminate\Support\Facades\App;

class PrintPaymentController extends Controller
{
    /**
     * Imprimer les paiements par date
     * @param string $date
     * @param mixed $categoryFeeId
     * @return mixed
     */
    public function printPaymentsByDate(
        string $date,
        $categoryFeeId,
        int $feeId,
        int $sectionId,
        int $optionId,
        int $classRoomId
    ): mixed {
        $categoryFee = CategoryFee::find($categoryFeeId);
        $section = Section::find($sectionId);
        $option = Option::find($optionId);
        $classRoom = ClassRoom::find($classRoomId);
        $payments = PaymentFeature::getList(
            $date,
            '',
            null,
            $categoryFeeId,
            $feeId,
            $sectionId,
            $optionId,
            $classRoomId,
            true,
            null,
            1000
        );
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView(
            'prints.payment.print-payment-by-date',
            compact(
                ['payments', 'categoryFee', 'date', 'section', 'option', 'classRoom']
            )
        );
        return $pdf->stream();
    }
    /**
     * Imprimer la liste des paiements par mois
     * @param string $month
     * @param int $categoryFeeId
     * @param int $feeId
     * @param int $sectionId
     * @param int $optionId
     * @param int $classRoomId
     * @return mixed
     */
    public function printPaymentsByMonth(
        string $month,
        int $categoryFeeId,
        int $feeId,
        int $sectionId,
        int $optionId,
        int $classRoomId
    ): mixed {
        $categoryFee = CategoryFee::find($categoryFeeId);
        $section = Section::find($sectionId);
        $option = Option::find($optionId);
        $classRoom = ClassRoom::find($classRoomId);
        $payments = PaymentFeature::getList(
            null,
            $month,
            null,
            $categoryFeeId,
            $feeId,
            $sectionId,
            $optionId,
            $classRoomId,
            true,
            null,
            1000
        );
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView(
            'prints.payment.print-payment-by-month',
            compact(
                ['payments', 'categoryFee', 'month', 'section', 'option', 'classRoom']
            )
        );
        return $pdf->stream();
    }

    /**
     * Imprimer le bordereau par date
     * @param string $date
     * @return mixed
     */
    public function printPaymentSlipByDate(string $date): mixed
    {
        $categoryFees = CategoryFee::query()
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->get();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView(
            'prints.payment.print-payment-slip-by-date',
            compact(
                ['categoryFees', 'date']
            )
        );
        return $pdf->stream();
    }

    public function printPaymentSlipByMonth(string $month): mixed
    {
        $categoryFees = CategoryFee::query()
            ->where('school_id', School::DEFAULT_SCHOOL_ID())
            ->where('school_year_id', SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
            ->get();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView(
            'prints.payment.print-payment-slip-by-month',
            compact(
                ['categoryFees', 'month']
            )
        );
        return $pdf->stream();
    }
}
