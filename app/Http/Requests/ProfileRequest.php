<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProfileRequest extends Request
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
            'username' => 'required|between:2,5',
            'phone' => 'required_without_all:qq,wechat|numeric',
            'qq' => 'required_without_all:phone,wechat|numeric',
            'wechat' => 'required_without_all:phone,qq|alpha_dash',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => '请输入真实姓名',
            'username.between' => '请输入真实姓名',
            'phone.required_without_all' => '最少完善一种联系方式',
            'qq.required_without_all' => '最少完善一种联系方式',
            'wechat.required_without_all' => '最少完善一种联系方式-18位',
            'phone.numeric' => '手机格式不正确',
            'qq.numeric' => 'QQ格式不正确',
            'wechat.alpha_dash' => '微信账号格式不正确'
        ];
    }
}
