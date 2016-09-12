<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AuthRequest extends Request
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
            'studentId' => 'required|exists:mysql-auth.users,studentId',
            'password' => 'required '
        ];
    }

    public function messages()
    {
        return [
            'studentId.required' => '请输入学号',
            'studentId.exists' => '该学号还未绑定',
            'password.required' => '请输入密码'
        ];
    }
}
