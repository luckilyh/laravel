<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

/**
 * @param $request     请求的实例
 * @param $rules       验证的规则
 * @param $messages    自定义rules中的规则信息
 * @param $attributes  属性别名
 * @return array(验证成功)|string(验证失败)
 */
protected function verify($request,$rules,$messages,$attributes){
    try {
        return $request->validate($rules,$messages,$attributes);
    }catch (ValidationException $e){
        $error = $e->errors();
        return json_encode([
            'code' => 500,
            'msg' => reset($error)[0],
            'data' => [],
        ]);
    }
}
