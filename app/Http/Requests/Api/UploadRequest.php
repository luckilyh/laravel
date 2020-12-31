<?php

namespace App\Http\Requests\Api;

class UploadRequest extends FormRequest
{
    public function rules()
    {
        return [
            'type' => 'required',
            'file' => 'required|file',
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
