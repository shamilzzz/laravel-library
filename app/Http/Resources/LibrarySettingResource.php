<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LibrarySettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'max_borrow_days' => $this->max_borrow_days,
            'max_borrow_limit' => $this->max_borrow_limit,
            'borrow_charge' => $this->borrow_charge,
            'late_fee_per_day' => $this->late_fee_per_day,
        ];
    }
}