<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class OrdersFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'location'     => 'nullable|string',
            'client_name'  => 'nullable|string',
            'client_phone' => 'nullable|string',
            'status'       => 'nullable|string|exists:orders,status',
            'total_price'  => 'nullable|numeric',
            'deposited'    => 'nullable|numeric',
            'created_at'   => 'nullable|date',
            'updated_at'   => 'nullable|date',
        ];
    }
}
