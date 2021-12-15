<?php

namespace App\Http\Requests\Api;

class VerificationCodeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => [
                'required',
                'phone:CN,mobile',
            ]
        ];
    }

    public function attributes()
    {
        return [
            'phone' => '手机号',
        ];
    }
}
