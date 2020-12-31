<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UploadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function local(UploadRequest $request)
    {
        $file = $request->file('file');

        if (!$file->isValid()){
            return error('上传内容不是一个文件');
        }

        try {
            $path = Storage::disk('public')->putFile($request->type, $file);
        }catch (\Exception $e){
            return error($e->getMessage());
        }
        return success('上传成功', [
            'url' => '/files/'.$path,
            'base_url' => config('app.url')
        ]);
    }

    public function cloud(UploadRequest $request)
    {
        $file = $request->file('file');

        if (!$file->isValid()){
            return error('上传内容不是一个文件');
        }

        try {
            $path = Storage::disk('oss')->put($request->type, $file);
        }catch (\Exception $e){
            return error($e->getMessage());
        }
        return success('上传成功', [
            'url' => '/'.$path,
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
