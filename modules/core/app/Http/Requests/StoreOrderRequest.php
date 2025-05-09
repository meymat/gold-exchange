<?php

namespace Modules\core\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'price'       => 'required|numeric',
            'quantity'    => 'required|numeric',
        ];
    }
}
