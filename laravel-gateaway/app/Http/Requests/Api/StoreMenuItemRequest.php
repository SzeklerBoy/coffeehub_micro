<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuItemRequest extends FormRequest
{
    public function authorize(): true
    {
        // Authorization is done in the router
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3', 'unique:translations,name'],
            'description' => ['nullable', 'max:256'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            //            'image' => ['image', 'mimes:jpg,png,jpeg', 'max:2048'],
            'category' => ['required', 'min:3'],
            'ETA' => ['required', 'numeric', 'min:0'],
        ];
    }
}
