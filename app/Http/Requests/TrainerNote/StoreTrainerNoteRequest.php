<?php

namespace App\Http\Requests\TrainerNote;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTrainerNoteRequest extends FormRequest
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
            'id_member'   => 'required|exists:members,id',
            'id_schedule' => 'required|exists:trainer__schedules,id',
            'type'        => 'nullable|string|max:100',
            'priority'    => 'nullable|in:low,normal,high,urgent',
            'content'     => 'required|string',
            'weight'      => 'nullable|numeric',
            'body_fat'    => 'nullable|numeric',
            'muscle'      => 'nullable|numeric',
            'calories'    => 'nullable|integer',
            'status'      => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Tiêu đề ghi chú không được để trống.',
            'title.max'            => 'Tiêu đề không vượt quá 255 ký tự.',
            'id_member.required'   => 'Hội viên không được để trống.',
            'id_member.exists'     => 'Hội viên không tồn tại.',
            'id_schedule.required' => 'Buổi tập không được để trống.',
            'id_schedule.exists'   => 'Buổi tập không tồn tại.',
            'priority.in'          => 'Độ ưu tiên phải là: low, normal, high, urgent.',
            'content.required'     => 'Nội dung ghi chú không được để trống.',
            'weight.numeric'       => 'Cân nặng phải là số.',
            'body_fat.numeric'     => 'Tỷ lệ mỡ phải là số.',
            'muscle.numeric'       => 'Cơ bắp phải là số.',
            'calories.integer'     => 'Calories phải là số nguyên.',
        ];
    }
}
