<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
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
            'id_schedule'             => 'required|exists:trainer__schedules,id',
            'attendances'             => 'required|array',
            'attendances.*.id_member' => 'required|exists:members,id',
            'attendances.*.status'    => 'required|in:0,1,2',
        ];
    }

    public function messages(): array
    {
        return [
            'id_schedule.required'             => 'Buổi tập không được để trống.',
            'id_schedule.exists'               => 'Buổi tập không tồn tại.',
            'attendances.required'             => 'Danh sách điểm danh không được để trống.',
            'attendances.array'                => 'Danh sách điểm danh phải là mảng.',
            'attendances.*.id_member.required' => 'Hội viên không được để trống.',
            'attendances.*.id_member.exists'   => 'Hội viên không tồn tại.',
            'attendances.*.status.required'    => 'Trạng thái điểm danh không được để trống.',
            'attendances.*.status.in'          => 'Trạng thái điểm danh không hợp lệ (0, 1, 2).',
        ];
    }
}
