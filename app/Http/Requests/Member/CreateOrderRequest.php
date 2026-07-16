<?php

namespace App\Http\Requests\Member;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

   
   public function rules(): array
    {
        return [
            'id_schedule' => 'required|integer|exists:trainer__schedules,id',
            'payment_method' => 'nullable|string|max:50',
            'id_promotion' => 'nullable|integer|exists:promotions,id',
        ];
    }

    public function messages(): array
    {
        return [
            'id_schedule.required' => 'Vui lòng chọn lịch tập.',
            'id_schedule.integer' => 'Lịch tập không hợp lệ.',
            'id_schedule.exists' => 'Lịch tập không tồn tại.',

            'payment_method.string' => 'Phương thức thanh toán không hợp lệ.',

            'id_promotion.integer' => 'Mã khuyến mãi không hợp lệ.',
            'id_promotion.exists' => 'Mã khuyến mãi không tồn tại.',
        ];
    }
}
