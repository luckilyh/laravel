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
     * validate验证失败模板
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        $message = '';
        foreach (json_decode(json_encode($validator->errors()),1) as $error){
            $message = $error[0];
            break;
        }
        throw (new HttpResponseException(response()->json([
            'code' => 404,
            'msg'  => $message,
            'data' => []
        ])));
    }
}
