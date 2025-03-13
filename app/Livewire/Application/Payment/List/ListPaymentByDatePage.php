<?php

namespace App\Livewire\Application\Payment\List;

use App\Domain\Features\Configuration\FeeDataConfiguration;
use App\Domain\Features\Payment\PaymentFeature;
use App\Domain\Helpers\SmsNotificationHelper;
use App\Domain\Utils\AppMessage;
use App\Enums\RoleType;
use App\Models\CategoryFee;
use App\Models\Payment;
use App\Models\School;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ListPaymentByDatePage extends Component
{
    use WithPagination;
    protected $listeners = [
        "refreshPaymentList" => '$refresh',
    ];
    public ?string $date_filter = '';
    public int $category_fee_filter = 0;
    public int $per_page = 100;
    public ?CategoryFee $categoryFeeSelected;
    #[Url(as: 'q')]
    public $q = '';

    public function mount(): void
    {
        $this->date_filter = date('Y-m-d');
        if (Auth::user()->role->name == RoleType::SCHOOL_FINANCE) {
            $categoryFee = FeeDataConfiguration::getFirstCategoryFee();
        } else {
            $categoryFee = CategoryFee::query()->where('school_id', School::DEFAULT_SCHOOL_ID())
                ->where('school_year_id', School::DEFAULT_SCHOOL_ID())
                ->where('is_accessory', true)
                ->first();
        }
        $this->category_fee_filter = $categoryFee->id;
        $this->categoryFeeSelected = $categoryFee;
    }

    public function updatedCategoryFeeFilter($val): void
    {
        $this->categoryFeeSelected = CategoryFee::findOrFail($val);
    }

    public function edit(Payment $payment): void
    {
        $this->dispatch('paymentData', $payment);
    }

    public function delete(?Payment $payment): void
    {
        try {
            if (!$payment->is_paid) {
                $payment->delete();
                $this->dispatch('updated', ['message' => AppMessage::DATA_DELETED_SUCCESS]);
            } else {
                $this->dispatch('error', ['message' => 'Action impossible, car le paiement est déjà validé']);
            }
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function makeIsPaid(?Payment $payment): void
    {
        try {
            if ($payment->is_paid) {
                $payment->is_paid = false;
            } else {
                $payment->is_paid = true;
            }
            $payment->update();
            $this->dispatch('updated', ['message' => AppMessage::DATA_UPDATED_SUCCESS]);
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function sendSms(Payment $payment): void
    {

        try {
            if ($payment->smsPayment == null) {
                //$phone = '+243898337969';
                $phone =  $payment->registration->student->responsibleStudent->phone;
                $phone = str_replace([' ', '(', ')', '-'], '', $phone);
                $message = "C.S AQUILA, Cher parent, votre enfant "
                    . $payment->registration->student->name . " est en ordre avec le frais "
                    . $payment->scolarFee->name . ' Montant : ' . $payment->scolarFee->amount . " "
                    .  $payment->scolarFee->categoryFee->currency . " "
                    . format_fr_month_name($payment->month) . " , Merci pour votre confiance.";
                SmsNotificationHelper::sendOrangeSMS($phone, $message);
                $payment->smsPayment()->create([
                    'receiver' => $phone,
                    'message' => $message
                ]);
                $this->dispatch('added', ['message' => AppMessage::DATA_SAVED_SUCCESS]);
            } else {
                $this->dispatch('updated', ['message' => 'Message déjà envoyé']);
            }
        } catch (Exception $ex) {
            $this->dispatch('error', ['message' => $ex->getMessage()]);
        }
    }

    public function render(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
    {
        return view('livewire.application.payment.list.list-payment-by-date-page', [
            'payments' => PaymentFeature::getList(
                $this->date_filter,
                '',
                $this->q,
                $this->category_fee_filter,
                null,
                null,
                null,
                null,
                null,
                Auth::id(),
                $this->per_page
            ),
            'total_payments' => PaymentFeature::getTotal(
                $this->date_filter,
                '',
                $this->category_fee_filter,
                null,
                null,
                null,
                0,
                true,
                null,
                Auth::id(),
                'CDF'
            ),
            'categoryFees' => FeeDataConfiguration::getListCategoryFee(100)
        ]);
    }
}
