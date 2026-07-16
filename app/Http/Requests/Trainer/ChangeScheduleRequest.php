<?php

namespace App\Http\Requests\Trainer;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ChangeScheduleRequest extends FormRequest
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
            'old_schedule_id' => 'required|exists:trainer__schedules,id',
            'date'            => 'required|date',
            'start_time'      => 'required',
            'end_time'        => 'required',
            'reason'          => 'required|string|max:500',
        ];
    }
    public function messages()
    {
        return [
            'old_schedule_id.required' => 'Thiếu lịch cần đổi.',
            'date.required' => 'Vui lòng chọn ngày.',
            'start_time.required' => 'Vui lòng chọn giờ bắt đầu.',
            'end_time.required' => 'Vui lòng chọn giờ kết thúc.',
            'reason.required' => 'Vui lòng nhập lý do.',
        ];
    }
}
