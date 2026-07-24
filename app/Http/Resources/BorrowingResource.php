<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PaymentResource;

class BorrowingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'member' => new UserResource($this->whenLoaded('user')),

            'book_copy' => new BookCopyResource($this->whenLoaded('bookCopy')),

            'borrowed_at' => $this->borrowed_at,

            'due_date' => $this->due_date,

            'returned_at' => $this->returned_at,

            'status' => $this->status,

            'payment' => new PaymentResource(
                $this->whenLoaded('payment')
            ),
        ];
    }
}