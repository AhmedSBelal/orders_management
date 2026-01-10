<?php

namespace App\Http\Requests\Orders;

use App\Enums\OrderStatuses;
use App\Enums\PaymentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderCreateRequest extends FormRequest
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
            "status_id"    => "required|exists:order_statuses,id",
            "client_phone" => "nullable|string|max:255|min:3",
            "city"         => "required|string|max:255|min:3",
            "deposited"    => "nullable|integer|min:0",
            "come_from"    => "nullable|string|max:255|min:3",
            "payment_status" => ['nullable', Rule::in(PaymentStatus::values())],
            "products"     => "required|array",
            "products.*"   => "integer|exists:products,id",
            "colors"       => "required|array",
            "colors.*"     => "integer|exists:colors,id",
            "sizes"        => "required|array",
            "sizes.*"      => "numeric",
            "quantities"   => "required|array",
            "quantities.*" => "integer",
            "is_done"      => "array",
            "is_done.*"    => "boolean",
            "notes"        => "nullable|string|max:255|min:3",
            "total_price_after_discount" => "nullable|numeric|min:0",
            "photos"       => "nullable|array|max:5", // أضفنا هذا السطر
            "photos.*"     => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048", // وأضفنا هذا السطر
            'is_wholesale' => 'sometimes|boolean',
        ];
    }
}
