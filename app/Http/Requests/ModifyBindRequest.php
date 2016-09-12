<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ModifyBindRequest extends Request
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
            'studentId' => 'required',
            'openId' => 'required',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'studentId.required' => '请输入学号',
            'openId.required' => '操作失败',
            'password.required' => '请输入密码',
        ];
    }
}
