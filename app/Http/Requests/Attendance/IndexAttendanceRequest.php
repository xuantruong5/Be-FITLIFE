<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class IndexAttendanceRequest extends FormRequest
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
            'id_schedule' => 'required|exists:trainer__schedules,id',
        ];
    }

    public function messages(): array
    {
        return [
            'id_schedule.required' => 'Buổi tập không được để trống.',
            'id_schedule.exists'   => 'Buổi tập không tồn tại.',
        ];
    }
}
