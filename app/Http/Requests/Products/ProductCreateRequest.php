<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'cost' => 'required|numeric',
            'type' => 'required|string|max:255',
            'tailor_name' => 'nullable|string|max:255',
             // إضافة قواعد التحقق للصور
            'photos' => 'nullable',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // كل صورة يجب أن تكون صورة حقيقية، من الأنواع المحددة، وحجمها الأقصى 2 ميجابايت (2048 كيلوبايت)
        ];
    }

     /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'photos.*.image' => 'يجب أن يكون كل ملف مرفوع صورة.',
            'photos.*.mimes' => 'صيغ الصور المسموح بها هي: jpeg, png, jpg, gif فقط.',
            'photos.*.max' => 'حجم كل صورة يجب ألا يتجاوز 2 ميجابايت.',
            'price.min' => 'يجب أن يكون سعر المنتج رقماً موجباً.',
            'cost.min' => 'يجب أن تكون تكلفة المنتج رقماً موجباً.',
        ];
    }
}
