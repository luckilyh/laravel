<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{
    public function test(Request $request){
        User::findOrFail(2);
        die();
        $time = time() - (time()-30641);
        return Carbon::now()->subSecond($time)->diffForHumans();
        die();
        // Mail::send()的返回值为空，所以可以其他方法进行判断
        Mail::send('emails.authentication',['url'=>'https://www.baidu.com/'],function($message){
            $to = '211996735@qq.com'; $message ->to($to)->subject('注册');
        });
        dd(Mail::failures());
        die();
        $data = verify($request,[
            'phone' => 'required',
        ],[],[]);

        dd($data);
    }
}
