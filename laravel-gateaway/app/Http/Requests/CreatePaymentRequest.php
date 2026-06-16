<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentRequest extends FormRequest
{
    public function authorize(): User|Authenticatable
    {
        return auth()->user();
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array'],
            'items.*.menu_item_id' => ['required', 'integer'],
            'items.*.quantity' => ['required', 'integer', 'min:0'],
        ];
    }
}
