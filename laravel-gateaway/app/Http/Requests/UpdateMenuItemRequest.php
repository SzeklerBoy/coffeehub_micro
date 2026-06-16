<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuItemRequest extends FormRequest
{
    public function authorize(): User|Authenticatable
    {
        return auth()->user();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3'],
            'description' => ['nullable', 'max:256'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['image', 'mimes:jpg,png,jpeg', 'max:2048'],
        ];
    }
}
