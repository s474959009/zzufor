<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateTagRequest extends Request
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
            //所选标签存在于tags表，而且category=1
            'rootId' => 'not_in:0|exists:tags,id,category,1',
            'tagName' => 'required|unique:tags'
        ];
    }

    public function messages()
    {
        return [
            'rootId.not_in' => '请先选择标签类别',
            'rootId.exists' => '不存在该标签类别',
            'tagName.required' => '请输入标签名称',
            'tagName.unique' => '该标签已存在'
        ];
    }
}
