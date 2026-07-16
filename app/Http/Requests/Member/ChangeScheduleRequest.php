<?php

namespace App\Http\Requests\Member;

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
            'id_schedule' => [ 'required', 'integer','exists:trainer__schedules,id'],
            'date' => [ 'required','date' ],
            'start_time' => [ 'required','date_format:H:i' ],
            'end_time' => [ 'required', 'date_format:H:i', 'after:start_time' ],
            'reason' => ['required', 'string', 'max:255']
        ];
    }
    public function messages(): array
    {
        return [ 
            'id_schedule.required'  => 'Vui lòng chọn buổi tập hiện tại',              
            'id_schedule.exists'  => 'Buổi tập không tồn tại',           
            'date.required' => 'Vui lòng chọn ngày đổi lịch',        
            'date.date'  => 'Ngày không hợp lệ',
            'start_time.required'  => 'Vui lòng chọn giờ bắt đầu',
            'start_time.date_format' => 'Giờ bắt đầu không đúng định dạng',
            'end_time.required' => 'Vui lòng chọn giờ kết thúc',
            'end_time.after'  => 'Giờ kết thúc phải sau giờ bắt đầu',
            'reason.required'  => 'Vui lòng nhập lý do đổi lịch',
        ];
    }
}
