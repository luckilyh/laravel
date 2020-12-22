<?php
/**
 * 自定义状态码输出
 */
function apiOutPut($code, $msg, $data = []){
    return response()->json([
        'code' => $code,
        'msg' => $msg,
        'data' => $data,
    ]);
}

/**
 * 成功
 */
function success($msg, $data = [])
{
    return response()->json([
        'code' => 200,
        'msg' => $msg,
        'data' => $data,
    ]);
}

/**
 * 失败
 */
function error($msg, $data = [])
{
    return response()->json([
        'code' => 404,
        'msg' => $msg,
        'data' => $data,
    ]);
}

/**
 * 异常
 */
function exception($msg, $data = [])
{
    return response()->json([
        'code' => 500,
        'msg' => $msg,
        'data' => $data,
    ]);
}
