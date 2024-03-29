<?php

namespace App\Http\Controllers\Api;

use App\Models\SystemSetting;
use App\Models\Fragment;
use Hashids;
use Illuminate\Http\Request;

class PersonalController extends Controller
{
    /**
     * 碎片详情
     * @param id 碎片id
     * @return \Illuminate\Http\JsonResponse
     */
    public function fragment(Request $request)
    {
        $forbidden_id = [];
        if (empty($request->id)) {
            return error('id 不能为空');
        }

        $id = hashid_decode($request->id);
        if ($id === false) {
            return error('哈希 id 解析失败');
        }

        if (in_array($id, $forbidden_id)) {
            return error('访问错误的项');
        }

        $result = Fragment::find($id);

        return success('查询成功', $result);
    }
}
