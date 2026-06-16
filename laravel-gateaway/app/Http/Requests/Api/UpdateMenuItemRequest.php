<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuItemRequest extends FormRequest
{
    public function authorize(): true
    {
        // Authorization is done in the router
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['min:3'],
            'description' => ['max:256'],
            'quantity' => ['numeric', 'min:0'],
            'price' => ['numeric', 'min:0'],
            'ETA' => ['numeric', 'min:0'],
            //            'image' => ['image', 'mimes:jpg,png,jpeg', 'max:2048'],
        ];
    }
}
