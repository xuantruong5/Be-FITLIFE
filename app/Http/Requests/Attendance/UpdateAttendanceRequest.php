<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
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
            'status'         => 'sometimes|required|in:0,1,2',
            'check_in_time'  => 'nullable|date',
            'check_out_time' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required'    => 'Trạng thái điểm danh không được để trống.',
            'status.in'          => 'Trạng thái điểm danh không hợp lệ (0, 1, 2).',
            'check_in_time.date' => 'Giờ vào không đúng định dạng ngày giờ.',
            'check_out_time.date'=> 'Giờ ra không đúng định dạng ngày giờ.',
        ];
    }
}
