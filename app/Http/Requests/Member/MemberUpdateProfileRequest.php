<?php

namespace App\Http\Requests\Member;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MemberUpdateProfileRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:0,1',
            'address' => 'nullable|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Tên không được để trống.',
            'name.max' => 'Tên không được vượt quá 255 ký tự.',

            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',

            'date_of_birth.date' => 'Ngày sinh không đúng định dạng.',

            'gender.in' => 'Giới tính không hợp lệ.',

            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
        ];
    }
}
