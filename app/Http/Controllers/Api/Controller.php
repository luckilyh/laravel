<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * 常量状态码
     */
    public $mStatus = [
        401 => '未登录或登录已失效'
    ];

    /**
     * 自定义状态码输出
     */
    public function apiOutPut($code, $msg, $data = []){
        return response()->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
    }

    /**
     * 失败
     */
    public function error($msg, $data = [])
    {
        return response()->json([
            'code' => 404,
            'msg' => $msg,
            'data' => $data,
        ]);
    }

    /**
     * 成功
     */
    public function success($msg, $data = [])
    {
        return response()->json([
            'code' => 200,
            'msg' => $msg,
            'data' => $data,
        ]);
    }

    /**
     * 异常
     */
    public function exception($msg, $data = [])
    {
        return response()->json([
            'code' => 500,
            'msg' => $msg,
            'data' => $data,
        ]);
    }
}