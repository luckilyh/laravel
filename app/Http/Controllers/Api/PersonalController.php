<?php

namespace App\Http\Controllers\Api;

use App\Models\SystemSetting;
use App\Models\Fragment;
use Illuminate\Http\Request;

class PersonalController extends Controller
{
    /**
     * 系统设置列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function systemSettingList(){
        $result = SystemSetting::get();

        return success('查询成功',$result);
    }

    /**
     * 系统设置详情
     * @param id 系统设置id
     * @return \Illuminate\Http\JsonResponse
     */
    public function systemSettingInfo(Request $request){
        if (empty($request->id)){
            return error('id 不能为空');
        }

        $result = SystemSetting::find($request->id);

        return success('查询成功',$result);
    }

    /**
     * 碎片详情
     * @param id 碎片id
     * @return \Illuminate\Http\JsonResponse
     */
    public function fragment(Request $request){
        if (empty($request->id)){
            return error('id 不能为空');
        }

        $result = Fragment::find($request->id);

        return success('查询成功',$result);
    }
}
