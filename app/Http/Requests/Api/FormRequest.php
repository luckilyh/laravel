<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FormRequest extends BaseFormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * 重写验证错误输出信息
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator) {
        $error= $validator->errors()->all();
        throw new HttpResponseException(response()->json(['code'=>404,'msg'=>$error[0],'data'=>[]]));
    }
}