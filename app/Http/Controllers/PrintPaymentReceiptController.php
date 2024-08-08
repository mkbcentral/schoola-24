<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
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
}
