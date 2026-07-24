<?php

namespace App\Http\Requests\LibrarySetting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLibrarySettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'max_borrow_days' => ['required', 'integer', 'min:1'],
            'max_borrow_limit' => ['required', 'integer', 'min:1'],
            'borrow_charge' => ['required', 'numeric', 'min:0'],
            'late_fee_per_day' => ['required', 'numeric', 'min:0'],
        ];
    }
}