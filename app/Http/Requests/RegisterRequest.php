<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class RegisterRequest extends Request
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|unique:users',
            'password' => 'required|between:6,18',
            'repassword' => 'required|same:password'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => '请输入邮箱',
            'email.email' => '请输入正确的邮箱',
            'email.unique' => '该邮箱已被注册',
            'password.required' => '请输入密码',
            'password.between' => '密码长度只能为6-18位',
            'repassword.same' => '两次输入密码不一致',
            'repassword.required' => '请确认密码'
        ];
    }
}
