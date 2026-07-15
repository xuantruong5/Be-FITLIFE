<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class StorePackageRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:packages,slug',
            'price' => 'required|numeric|min:0',
            'duration_months' => 'required|integer|in:1,3,6,12',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
            'is_popular' => 'nullable|boolean',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Tên gói tập không được để trống.',
            'slug.unique' => 'Slug đã tồn tại.',
            'price.required' => 'Giá gói là bắt buộc.',
            'price.numeric' => 'Giá gói phải là số.',
            'duration_months.required' => 'Vui lòng chọn thời hạn.',
            'duration_months.in' => 'Thời hạn không hợp lệ.',
            'status.required' => 'Vui lòng chọn trạng thái.',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'Authorization failed'
        ], 403));
    }
}
