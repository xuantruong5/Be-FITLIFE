<?php

namespace App\Http\Requests\TrainerSchedule;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTrainerScheduleRequest extends FormRequest
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
            'title'       => 'required|string|max:255',
            'date'        => 'required|date',
            'start_time'  => 'required',
            'end_time'    => 'required|after:start_time',
            'room'        => 'required|string|max:100',
            'max_members' => 'nullable|integer|min:1',
            'id_branch'   => 'required|exists:branches,id',
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
            'end_time.after'      => 'Giờ kết thúc phải sau giờ bắt đầu.',
            'room.required'       => 'Phòng tập không được để trống.',
            'room.max'            => 'Phòng tập không vượt quá 100 ký tự.',
            'max_members.min'     => 'Số thành viên tối đa phải ít nhất là 1.',
            'id_branch.required'  => 'Chi nhánh không được để trống.',
            'id_branch.exists'    => 'Chi nhánh không tồn tại.',
        ];
    }
}
