<?php

namespace App\Http\Controllers\Api;

use App\Models\About;
use Illuminate\Http\Request;

class PersonalController extends Controller
{
    /**
     * 关于我们资料详情
     * @param id 关于我们资料id
     * @return \Illuminate\Http\JsonResponse
     */
    public function about(Request $request){
        if (empty($request->id)){
            return $this->error('id 不能为空');
        }

        $result = About::find($request->id);

        return $this->success('查询成功',$result);
    }
}
