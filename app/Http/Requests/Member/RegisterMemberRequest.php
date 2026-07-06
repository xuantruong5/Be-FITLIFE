<?php

namespace App\Http\Requests\Member;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterMemberRequest extends FormRequest
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
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:members',
            'phone'         => 'required|digits:10',
            'password'      => 'required|string|min:6',
            're_password'   => 'same:password',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required'         => 'Họ tên không được để trống',
            'email.required'        => 'Email không được để trống',
            'email.email'           => 'Email không đúng định dạng',
            'email.unique'          => 'Email đã tồn tại',
            'phone.required'        => 'Số điện thoại không được để trống',
            'phone.digits'          => 'Số điện thoại phải đủ 10 số',
            'password.required'     => 'Mật khẩu không được để trống',
            'password.min'          => 'Mật khẩu phải có ít nhất 6 ký tự',
            're_password.same'      => 'Mật khẩu nhập lại không khớp',
        ];
    }
}
