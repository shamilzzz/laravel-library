<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
            ],

            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->route('user')),
            ],

            'password' => [
                'sometimes',
                'confirmed',
                'min:8',
            ],

            'role' => [
                'sometimes',
                new Enum(UserRole::class),
            ],

            'status' => [
                'sometimes',
                new Enum(UserStatus::class),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'address' => [
                'nullable',
                'string',
            ],
        ];
    }
}