<?php

namespace App\Http\Requests\Package;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePackageRequest extends FormRequest
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
        $id = $this->route('id');

        return [
            'name'          => 'sometimes|required|string|max:255',
            'slug'          => 'sometimes|required|string|unique:packages,slug,' . $id,
            'price'         => 'sometimes|required|integer|min:0',
            'duration_days' => 'sometimes|required|integer|min:1',
            'description'   => 'nullable|string',
            'status'        => 'nullable|in:0,1',
            'is_popular'    => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'          => 'Tên gói tập không được để trống.',
            'slug.required'          => 'Slug không được để trống.',
            'slug.unique'            => 'Slug đã tồn tại.',
            'price.required'         => 'Giá gói tập không được để trống.',
            'price.min'              => 'Giá gói tập phải lớn hơn hoặc bằng 0.',
            'duration_days.required' => 'Số ngày sử dụng không được để trống.',
            'duration_days.min'      => 'Số ngày sử dụng phải ít nhất là 1.',
            'status.in'              => 'Trạng thái không hợp lệ.',
        ];
    }
}
