<?php

namespace App\Http\Requests\Api;

class UploadRequest extends FormRequest
{
    public function rules()
    {
        return [
            'type' => 'required',
            'files' => 'required|array',
            'files.*' => 'file',
        ];
    }

    public function attributes()
    {
        return [
            'type' => '文件类型',
            'files' => '文件',
        ];
    }
}