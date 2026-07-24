<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBorrowingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'member_id' => ['required', 'exists:users,id'],
            'book_id' => ['required', 'exists:books,id'],
            'borrow_days' => ['required', 'integer', 'min:1'],
        ];
    }
}