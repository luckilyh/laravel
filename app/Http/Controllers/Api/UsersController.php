<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Api\UserRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\User as UserResource;

class UsersController extends Controller
{
    public function store(UserRequest $request)
    {
        $verifyData = \Cache::get($request->verification_key);

        if (!$verifyData) {
            return error('验证码已失效');
        }

        if (!hash_equals($verifyData['code'], $request->verification_code)) {
            return error('验证码错误');
        }

        if(User::where('phone',$verifyData['phone'])->first()){
            return error('该手机号已被注册');
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $verifyData['phone'],
            'password' => Hash::make($request->password),
        ]);

        // 清除验证码缓存
        \Cache::forget($request->verification_key);

        return success('注册成功',new UserResource($user));
    }

    public function me(Request $request)
    {
        $user = auth()->user();

        return success('查询成功',new UserResource($user));
    }
}
