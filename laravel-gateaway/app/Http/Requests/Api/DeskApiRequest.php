<?php

namespace App\Http\Requests\Api;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Http\FormRequest;

class DeskApiRequest extends FormRequest
{
    public function authorize(): User|Authenticatable
    {
        return auth()->user();
    }

    public function rules(): array
    {
        return [
            'desks' => ['required', 'array'],
            'desks.*.x' => ['required', 'numeric'],
            'desks.*.y' => ['required', 'numeric'],
            'desks.*.nrOfSeats' => ['required', 'integer', 'min:1', 'max:10'],
        ];
    }
}
