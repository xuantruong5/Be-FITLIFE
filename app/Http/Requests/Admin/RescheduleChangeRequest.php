<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RescheduleChangeRequest extends FormRequest
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
            'id' => 'required|exists:reschedules,id',
            'status' => 'required|in:0,2',
            // 'trainer_note' => 'nullable|string|max:500',
        ];
    }
    public function messages(): array
    {
        return [
            'id.required' => 'ID yêu cầu không được để trống.',
            'id.exists' => 'Yêu cầu đổi lịch không tồn tại.',

            'status.required' => 'Trạng thái không được để trống.',
            'status.in' => 'Trạng thái chỉ được phép là 0 (Đã duyệt) hoặc 2 (Từ chối).',

            // 'trainer_note.max' => 'Ghi chú không được vượt quá 500 ký tự.',
        ];
    }

}
