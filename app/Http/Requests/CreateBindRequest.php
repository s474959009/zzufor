<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateBindRequest extends Request
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
            'studentId' => 'required|unique:mysql-auth.users',
            'openId' => 'required|unique:mysql-auth.users',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'studentId.required' => '请输入学号',
            'studentId.unique' => '该学号已被绑定',
            'openId.required' => '操作失败',
            'openId.unique' => '已绑定学号或已解除绑定',    
            'password.required' => '请输入密码',
        ];
    }
}
