<?php

namespace App\Http\Requests\Trainer;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TrainerScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return True;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'         => 'required|string|max:255',
            'date'          => 'required|date',
            'start_time'    => 'required|date_format:H:i',
            'end_time'      => 'required|date_format:H:i|after:start_time',
            'room'          => 'nullable|string|max:100',
            'id_package'    => 'required|exists:packages,id',
            'id_branch'     => 'required|exists:branches,id',
            'max_members'   => 'required|integer|min:1',
            'note'          => 'nullable|string',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'Vui lòng nhập tên khóa tập.',
            'date.required' => 'Vui lòng chọn ngày.',
            'start_time.required' => 'Vui lòng chọn giờ bắt đầu.',
            'end_time.after' => 'Giờ kết thúc phải lớn hơn giờ bắt đầu.',
            'id_package.required' => 'Vui lòng chọn gói tập.',
            'id_package.exists' => 'Gói tập không tồn tại.',
            'id_branch.required' => 'Vui lòng chọn chi nhánh.',
            'id_branch.exists' => 'Chi nhánh không tồn tại.',
            'max_members.required' => 'Vui lòng nhập số lượng học viên.',
        ];
    }
}
