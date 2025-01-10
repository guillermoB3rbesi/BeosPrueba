<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'currency_id' => 'required|exists:currencies,id',
            'tax_cost' => 'required|numeric|min:0',
            'manufacturing_cost' => 'required|numeric|min:0',
        ];
    }

    /**
     * Mensajes personalizados para las reglas de validación.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio.',
            'description.required' => 'La descripción del producto es obligatoria.',
            'price.required' => 'El precio es obligatorio y debe ser un número.',
            'currency_id.exists' => 'La divisa seleccionada no es válida.',
            'tax_cost.required' => 'El costo de impuestos es obligatorio y debe ser un número.',
            'manufacturing_cost.required' => 'El costo de fabricación es obligatorio y debe ser un número.',
        ];
    }
}
