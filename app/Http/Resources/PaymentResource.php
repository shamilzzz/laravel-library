<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'borrow_charge' => $this->borrow_charge,
            'late_fee' => $this->late_fee,
            'damage_fee' => $this->damage_fee,
            'lost_book_charge' => $this->lost_book_charge,
            'total_amount' => $this->total_amount,

            'borrowing' => new BorrowingResource(
                $this->whenLoaded('borrowing')
            ),

            'created_at' => $this->created_at,
        ];
    }
}