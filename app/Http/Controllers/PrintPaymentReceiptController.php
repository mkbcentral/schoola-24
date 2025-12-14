<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentRegularization;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\App;

class PrintPaymentReceiptController extends Controller
{
    public function printReceipt(Payment $payment)
    {
        $payment->is_paid = true;
        $payment->update();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('prints.print-receipt', compact(['payment']));

        return $pdf->stream();
    }

    public function printRegReceipt(PaymentRegularization $paymentRegularization)
    {
        $lastSchoolYear = SchoolYear::where('is_last_year', true)->first();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('prints.print-reg-receipt', compact(['paymentRegularization', 'lastSchoolYear']));

        return $pdf->stream();
    }
}
