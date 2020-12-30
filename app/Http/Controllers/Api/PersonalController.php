<?php

namespace App\Http\Controllers\Api;

use App\Models\About;
use App\Models\Sundry;
use Illuminate\Http\Request;

class PersonalController extends Controller
{
    /**
     * 关于我们资料列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function aboutList(){
        $result = About::get();

        return success('查询成功',$result);
    }

    /**
     * 关于我们资料详情
     * @param id 关于我们资料id
     * @return \Illuminate\Http\JsonResponse
     */
    public function aboutInfo(Request $request){
        if (empty($request->id)){
            return error('id 不能为空');
        }

        $result = About::find($request->id);

        return success('查询成功',$result);
    }

    /**
     * 杂项内容详情
     * @param id 杂项内容id
     * @return \Illuminate\Http\JsonResponse
     */
    public function sundry(Request $request){
        if (empty($request->id)){
            return error('id 不能为空');
        }

        $result = Sundry::find($request->id);

        return success('查询成功',$result);
    }
}
