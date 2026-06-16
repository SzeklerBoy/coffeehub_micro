<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['string', 'numeric', 'unique:'.User::class],
            'role' => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
            'password' => ['required', 'confirmed', Rules\Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols(),
            ],
        ];
    }
}
