<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],

            'isbn' => ['required', 'string', 'max:20', 'unique:books,isbn'],

            'category_id' => ['required', 'exists:categories,id'],

            'publication_year' => [
                'required',
                'digits:4',
                'integer',
                'min:1000',
                'max:' . date('Y'),
            ],

            'description' => ['nullable', 'string'],

            'authors' => ['required', 'array', 'min:1'],

            'authors.*' => ['exists:authors,id'],
            
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }
}