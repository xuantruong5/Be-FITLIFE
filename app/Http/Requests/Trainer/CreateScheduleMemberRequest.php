<?php

namespace App\Http\Requests\Trainer;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateScheduleMemberRequest extends FormRequest
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
            'id_member_package'=>'required|exists:member_packages,id',
            'start_date'=>'required|date',
            'end_date'=>'required|date|after_or_equal:start_date',
            'start_time'=>'required',
            'end_time'=>'required',
            'week_days'=>'required|array|min:1',
            'week_days.*'=>'integer|between:0,6'
        ];
    }
}
