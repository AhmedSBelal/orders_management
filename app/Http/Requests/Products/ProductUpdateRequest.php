<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'wholesale_price' => 'required|numeric|min:0|lte:price',
            'cost' => 'required|numeric|min:0',
            'tailor_name' => 'nullable|string|max:255',
            'type' => 'required|string|max:255',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'price.required' => 'السعر القطاعي مطلوب',
            'wholesale_price.required' => 'سعر الجمله مطلوب',
            'wholesale_price.lte' => 'سعر الجمله يجب أن يكون أقل من أو يساوي السعر القطاعي',
            'photos.*.image' => 'يجب أن يكون كل ملف مرفوع صورة.',
            'photos.*.mimes' => 'صيغ الصور المسموح بها هي: jpeg, png, jpg, gif فقط.',
            'photos.*.max' => 'حجم كل صورة يجب ألا يتجاوز 2 ميجابايت.',
            'price.min' => 'يجب أن يكون سعر المنتج رقماً موجباً.',
            'cost.min' => 'يجب أن تكون تكلفة المنتج رقماً موجباً.',
        ];
    }
}