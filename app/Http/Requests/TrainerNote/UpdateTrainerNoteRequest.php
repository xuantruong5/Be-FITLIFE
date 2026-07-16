<?php

namespace App\Http\Requests\TrainerNote;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTrainerNoteRequest extends FormRequest
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
            'title'    => 'sometimes|required|string|max:255',
            'type'     => 'nullable|string|max:100',
            'priority' => 'nullable|in:low,normal,high,urgent',
            'content'  => 'sometimes|required|string',
            'weight'   => 'nullable|numeric',
            'body_fat' => 'nullable|numeric',
            'muscle'   => 'nullable|numeric',
            'calories' => 'nullable|integer',
            'status'   => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'   => 'Tiêu đề ghi chú không được để trống.',
            'title.max'        => 'Tiêu đề không vượt quá 255 ký tự.',
            'priority.in'      => 'Độ ưu tiên phải là: low, normal, high, urgent.',
            'content.required' => 'Nội dung ghi chú không được để trống.',
            'weight.numeric'   => 'Cân nặng phải là số.',
            'body_fat.numeric' => 'Tỷ lệ mỡ phải là số.',
            'muscle.numeric'   => 'Cơ bắp phải là số.',
            'calories.integer' => 'Calories phải là số nguyên.',
        ];
    }
}
