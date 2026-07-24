<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],

            'isbn' => [
                'required',
                'string',
                'max:20',
                Rule::unique('books', 'isbn')
                    ->ignore($this->route('book')),
            ],

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