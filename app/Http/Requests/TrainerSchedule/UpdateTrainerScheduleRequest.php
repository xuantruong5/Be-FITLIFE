<?php

namespace App\Http\Requests\TrainerSchedule;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTrainerScheduleRequest extends FormRequest
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
            'title'       => 'sometimes|required|string|max:255',
            'date'        => 'sometimes|required|date',
            'start_time'  => 'sometimes|required',
            'end_time'    => 'sometimes|required',
            'room'        => 'sometimes|required|string|max:100',
            'max_members' => 'nullable|integer|min:1',
            'note'        => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'      => 'Tên buổi tập không được để trống.',
            'title.max'           => 'Tên buổi tập không vượt quá 255 ký tự.',
            'date.required'       => 'Ngày tập không được để trống.',
            'date.date'           => 'Ngày tập không đúng định dạng.',
            'start_time.required' => 'Giờ bắt đầu không được để trống.',
            'end_time.required'   => 'Giờ kết thúc không được để trống.',
            'room.required'       => 'Phòng tập không được để trống.',
            'room.max'            => 'Phòng tập không vượt quá 100 ký tự.',
            'max_members.min'     => 'Số thành viên tối đa phải ít nhất là 1.',
        ];
    }
}
