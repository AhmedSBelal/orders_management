<?php

namespace App\Http\Requests\Colors;

use Illuminate\Foundation\Http\FormRequest;

class ColorCreateRequest extends FormRequest
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
         $colorId = $this->route('color') ? $this->route('color')->id : null;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                // قاعدة التحقق الفريدة الصحيحة
                'unique:colors,name,' . $colorId
            ],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.unique' => 'اسم اللون هذا موجود بالفعل.',
            'photo.image' => 'يجب أن يكون الملف المرفوع صورة.',
            'photo.mimes' => 'صيغ الصور المسموح بها هي: jpeg, png, jpg, gif, svg.',
            'photo.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ];
    }
}
