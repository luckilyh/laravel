<?php

namespace App\Http\Controllers\Api;

use App\Models\Banner;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * 轮播图
     * @param type 类型 针对项目多轮播图使用,不传为查询所有
     * @return \Illuminate\Http\JsonResponse
     */
    public function banner(Request $request){
        $map = [];
        if ($request->type){
            if (in_array($request->type,['index'])){
                $map = ['type'=>$request->type];
            }else{
                return error('类型规格错误');
            }
        }

        $res = Banner::where($map)->get()->toArray();
        foreach ($res as $k=>$v){
            $res[$k]['image'] = config('app.url') . $v['image'];
        }

        return success('查询成功',$res);
    }
}
