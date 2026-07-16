<?php

namespace App\Http\Requests\TrainerSalary;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTrainerSalaryRequest extends FormRequest
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
            'id_trainer'  => 'required|exists:trainers,id',
            'month'       => 'required|integer|min:1|max:12',
            'year'        => 'required|integer|min:2020',
            'base_salary' => 'required|numeric|min:0',
            'bonus'       => 'nullable|numeric|min:0',
            'deduction'   => 'nullable|numeric|min:0',
            'note'        => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'id_trainer.required'  => 'HLV không được để trống.',
            'id_trainer.exists'    => 'HLV không tồn tại.',
            'month.required'       => 'Tháng không được để trống.',
            'month.min'            => 'Tháng phải từ 1 đến 12.',
            'month.max'            => 'Tháng phải từ 1 đến 12.',
            'year.required'        => 'Năm không được để trống.',
            'year.min'             => 'Năm phải từ 2020 trở lên.',
            'base_salary.required' => 'Lương cơ bản không được để trống.',
            'base_salary.min'      => 'Lương cơ bản phải lớn hơn hoặc bằng 0.',
            'bonus.min'            => 'Thưởng phải lớn hơn hoặc bằng 0.',
            'deduction.min'        => 'Khấu trừ phải lớn hơn hoặc bằng 0.',
        ];
    }
}
