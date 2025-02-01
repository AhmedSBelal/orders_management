<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
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
            "location"     => "required|string|max:255|min:3",
            "client_name"  => "required|string|max:255|min:3",
            "status"       => "exists:orders,status",
            "client_phone" => "string|max:255|min:3|nullable",
            "deposited"    => "nullable|integer",
            "products"     => "array",
            "products.*"   => "integer|exists:products,id",
            "colors"       => "array",
            "colors.*"     => "integer|exists:colors,id",
            "quantities"   => "array",
            "quantities.*" => "integer"
        ];
    }
}
