<?php

namespace App\Http\Controllers\Api;

use App\Models\Banner;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function banner(Request $request){
        $map = [];
        if ($request->type){
            if (in_array($request->type,['index'])){
                $map = ['type'=>$request->type];
            }else{
                return $this->error('类型规格错误');
            }
        }

        $res = Banner::where($map)->get()->toArray();
        foreach ($res as $k=>$v){
            $res[$k]['image'] = config('app.url') . $v['image'];
        }

        return $this->success('查询成功',$res);
    }
}
