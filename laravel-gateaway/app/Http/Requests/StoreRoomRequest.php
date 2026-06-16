<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): User|Authenticatable
    {
        return auth()->user();
    }

    public function rules(): array
    {
        return [
            'width' => ['required', 'numeric', 'min:1'],
            'length' => ['required', 'numeric', 'min:1'],
        ];
    }
}
