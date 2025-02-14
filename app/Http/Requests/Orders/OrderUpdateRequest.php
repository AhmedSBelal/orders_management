<?php

namespace App\Http\Requests\Orders;

use App\Enums\OrderStatuses;
use App\Enums\PaymentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderUpdateRequest extends FormRequest
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
            "status"       => ['required', Rule::in(OrderStatuses::values())],
            "client_phone" => "string|max:255|min:3|nullable",
            "city"         => "string|max:255|min:3|required",
            "post_office"  => "string|max:255|min:3|required",
            "deposited"    => "nullable|integer",
            "come_from"    => "nullable|string|max:255|min:3",
            "payment_status" => ['nullable', Rule::in(PaymentStatus::values())],
            "products"     => "array",
            "products.*"   => "integer|exists:products,id",
            "colors"       => "array",
            "colors.*"     => "integer|exists:colors,id",
            "sizes"        => "array",
            "sizes.*"      => "numeric",
            "quantities"   => "array",
            "quantities.*" => "integer",
            'is_done'      => "array",
            "is_done.*"    => "boolean",
        ];
    }
}
