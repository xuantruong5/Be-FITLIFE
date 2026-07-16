<?php

namespace App\Http\Requests\Branch;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'    => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone'   => 'nullable|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Tên chi nhánh không được để trống.',
            'name.max'         => 'Tên chi nhánh không vượt quá 255 ký tự.',
            'address.required' => 'Địa chỉ không được để trống.',
            'address.max'      => 'Địa chỉ không vượt quá 500 ký tự.',
            'phone.max'        => 'Số điện thoại không vượt quá 20 ký tự.',
        ];
    }
}
