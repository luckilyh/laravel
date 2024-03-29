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
 * @param $request     请求的实例
 * @param $rules       验证的规则
 * @param $messages    自定义rules中的规则信息
 * @param $attributes  属性别名
 * @return array(验证成功)|string(验证失败)
 */
function verify($request, $rules, $messages=[], $attributes=[]){
    try {
        return $request->validate($rules,$messages,$attributes);
    }catch (\Exception $e){
        $error = $e->errors();
        throw new Exception(reset($error)[0]);
    }
}

/**
 * 哈希 id 加密
 * @param $param
 * @return mixed
 */
function hashid_encode(int $id)
{
    return Hashids::encode($id);
}

/**
 * 哈希 id 解密
 * @param $data
 * @return false
 */
function hashid_decode($data)
{
    $result = Hashids::decode($data);
    if (empty($result) || empty($result[0])) {
        return false;
    }
    return $result[0];
}
