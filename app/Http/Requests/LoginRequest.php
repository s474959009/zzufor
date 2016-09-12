<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class LoginRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|between:6,18'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => '请输入邮箱',
            'email.email' => '请输入正确的邮箱',
            'email.exists' => '该邮箱未注册',
            'password.required' => '请输入密码',
            'password.between' => '密码长度只能为6-18位'
        ];
    }
}
