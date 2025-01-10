<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductPriceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'price' => 'required|numeric',
            'currency_id' => 'required|exists:currencies,id',
        ];
    }

    public function messages()
    {
        return [
            'price.required' => 'El monto es obligatorio.',
            'price.numeric' => 'El monto debe ser un número.',
            'price.min' => 'El monto debe ser mayor o igual a 0.',
            'currency_id.required' => 'La moneda es obligatoria.',
            'currency_id.exists' => 'La moneda seleccionada no es válida.',
        ];
    }
}
