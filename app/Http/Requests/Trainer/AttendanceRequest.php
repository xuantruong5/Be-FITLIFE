<?php

namespace App\Http\Requests\Trainer;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
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
            'id_schedule_member' => 'required|exists:schedule_members,id',
            'status' => 'required|integer|in:0,1,2,3',
        ];
    }
    public function messages(): array
    {
        return [
            'id_schedule_member.required' => 'Thiếu lịch tập.',
            'id_schedule_member.exists' => 'Lịch tập không tồn tại.',

            'status.required' => 'Vui lòng chọn trạng thái điểm danh.',
            'status.integer' => 'Trạng thái không hợp lệ.',
            'status.in' => 'Trạng thái chỉ được là 0, 1, 2 hoặc 3.',
        ];
    }
}
