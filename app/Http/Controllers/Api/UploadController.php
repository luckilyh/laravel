<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UploadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class UploadController extends Controller
{
    public function local(UploadRequest $request)
    {
        $arr = [];
        $error = [];
        foreach ($request->file('files') as $k=>$v) {
            if ($v->isValid()) {
                $path = Storage::disk('public')->putFile($request->type, $v);
                $arr[] = '/files/'.$path;
            }else{
                $error[] = $k;
            }
        }

        return success('上传成功', [
            'urls' => $arr,
            'errors' => $error,
            'base_url' => config('app.url')
        ]);
    }

    public function cloud(Request $request)
    {
        $arr = [];
        $error = [];
        foreach ($request->file('files') as $k=>$v) {
            if ($v->isValid()) {
                $path = Storage::disk('oss')->put($request->type, $v);
                $arr[] = '/' . $path;
            }else{
                $error[] = $k;
            }
        }

        return success('上传成功', [
            'urls' => $arr,
            'errors' => $error,
            'base_url' => config('filesystems.disks.oss.url')
        ]);
    }

    // 生成唯一文件名
    public static function name(){
        return md5(uniqid(microtime(true),true));
    }

    /**
     * 储存二进制流文件
     * @param $data //二进制流数据
     * @param string $type //存储目录
     * @param string $postfix //文件后缀
     * @param string $way //存储方式
     * @return mixed
     */
    public static function flow($data, $type='', $postfix='jpeg', $disk='public'){
        $fileName = self::name();
        Storage::disk($disk)->put($type.'/'.$fileName.'.'.$postfix,$data);
        return '/'.$type.'/'.$fileName.'.'.$postfix;
    }
}
