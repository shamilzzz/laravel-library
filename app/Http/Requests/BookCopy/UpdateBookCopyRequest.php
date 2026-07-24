<?php

namespace App\Http\Requests\BookCopy;

use App\Enums\BookCopyStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateBookCopyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                new Enum(BookCopyStatus::class),
            ],
        ];
    }
}