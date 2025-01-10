<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrencyRequest extends FormRequest
{
    public function authorize()
    {
        // Cambia esto a `true` si quieres que cualquier usuario pueda realizar esta solicitud.
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:currencies,name'],
            'symbol' => ['required', 'string', 'max:10'],
            'exchange_rate' => ['required', 'numeric', 'min:0.01'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre de la moneda es obligatorio.',
            'name.unique' => 'El nombre de la moneda ya está en uso.',
            'symbol.required' => 'El símbolo es obligatorio.',
            'symbol.max' => 'El símbolo no puede tener más de 10 caracteres.',
            'exchange_rate.required' => 'El tipo de cambio es obligatorio.',
            'exchange_rate.numeric' => 'El tipo de cambio debe ser un número.',
            'exchange_rate.min' => 'El tipo de cambio debe ser mayor a 0.',
        ];
    }
}
