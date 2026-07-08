<?php

namespace App\Http\Requests\TrainerSchedule;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RejectTrainerScheduleRequest extends FormRequest
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
            'admin_note' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'admin_note.required' => 'Lý do từ chối không được để trống.',
            'admin_note.string'   => 'Lý do từ chối phải là chuỗi ký tự.',
        ];
    }
}
