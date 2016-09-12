<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class RestRequest extends Request
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
            'password' => 'required|between:6,18',
            'repassword' => 'required|same:password'
        ];
    }

    public function messages()
    {
        return [
            'password.required' => '请输入密码',
            'password.between' => '密码长度只能为6-18位',
            'repassword.same' => '两次输入密码不一致',
            'repassword.required' => '请确认密码'
        ];
    }
}
