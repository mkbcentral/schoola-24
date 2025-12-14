<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->registration->student->name,
            'classRoom' => $this->registration->classRoom->getOriginalClassRoomName(),
            'reason' => $this->scolarFee->name,
            'amount' => $this->scolarFee->categoryFee->currency == 'CDF' ? $this->scolarFee->amount : $this->scolarFee->amount * $this->rate->amount,
            'month' => format_fr_month_name($this->month),
            'created_at' => $this->created_at->format('d/m/Y'),
        ];
    }
}
