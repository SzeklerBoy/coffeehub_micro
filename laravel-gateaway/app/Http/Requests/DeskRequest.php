<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Http\FormRequest;

class DeskRequest extends FormRequest
{
    public function authorize(): User|Authenticatable
    {
        return auth()->user();
    }

    public function rules(): array
    {
        return [
            'number_of_seats' => ['required', 'integer', 'min:1', 'max:8'],
            'description' => ['string', 'max:255'],
        ];
    }
}
