<?php

namespace App\Http\Requests\TrainerSalary;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTrainerSalaryRequest extends FormRequest
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
            'base_salary' => 'sometimes|required|numeric|min:0',
            'bonus'       => 'nullable|numeric|min:0',
            'deduction'   => 'nullable|numeric|min:0',
            'note'        => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'base_salary.required' => 'Lương cơ bản không được để trống.',
            'base_salary.min'      => 'Lương cơ bản phải lớn hơn hoặc bằng 0.',
            'bonus.min'            => 'Thưởng phải lớn hơn hoặc bằng 0.',
            'deduction.min'        => 'Khấu trừ phải lớn hơn hoặc bằng 0.',
        ];
    }
}
