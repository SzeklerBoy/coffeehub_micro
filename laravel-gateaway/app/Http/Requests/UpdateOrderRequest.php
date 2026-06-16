<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): User|Authenticatable
    {
        return auth()->user();
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:pending,cancelled,served,completed'],
        ];
    }
}
