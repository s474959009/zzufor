<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateGoodsRequest extends Request
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
            'goodsName' => 'required',
            'tags' => 'required',
            'desc' => 'max:200',
        ];
    }

    public function messages()
    {
        return [
            'desc.max' => '物品描述信息不能多于200字',
            'tags.required' => '请选择相关标签',
            'goodsName.required' => '请输入物品名称',
        ];
    }
}
