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
            "status_id"    => "required|exists:order_statuses,id",
            "client_phone" => "required|string|max:255|min:3",
            "city"         => "required|string|max:255|min:3",
            "deposited"    => "nullable|integer|min:0",
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
            "is_done"      => "nullable|array",
            "is_done.*"    => "nullable|boolean",
            "notes"        => "nullable|string|max:255|min:3",
            "total_price_after_discount" => "nullable|numeric|min:0",
            
            // Image validation - now optional
            "photos"       => "nullable|array|max:5",
            "photos.*"     => "image|mimes:jpeg,png,jpg,gif|max:2048",
            
            // Validation for deleting existing images
            "delete_images"   => "nullable|array",
            "delete_images.*" => "integer|exists:order_images,id",
        ];
    }
}
