<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Api\AuthorizationRequest;
use App\Http\Requests\Api\SocialAuthorizationRequest;
use Tymon\JWTAuth\Facades\JWTAuth;
use EasyWeChat\Factory;

class AuthorizationsController extends Controller
{
    public function login()
    {
        return apiOutPut(401, '未登录或登录已失效');
    }

    public function socialStore($type, SocialAuthorizationRequest $request)
    {
        //获取openid
        switch ($type) {
            case 'wechat_mini':
                if ($request->code) {
                    $app = Factory::miniProgram(config('wechat.mini_program.default'));
                    $user_session = $app->auth->session($request->code);
                    if (!isset($user_session['openid'])) {
                        return error($user_session['errmsg'], [
                            'errcode' => $user_session['errcode']
                        ]);
                    } else {
                        $openid = $user_session['openid'];
                    }
                } else {
                    $openid = $request->openid;
                }
                //数据库openid字段
                $field = 'weixin_openid';
                break;
        }

        $user = User::where($field, $openid)->first();
        if ($user) {
            $token = auth('api')->login($user);

            return $this->respondWithToken($token);
        } else {
            // 没有用户，默认创建一个用户(逻辑根据需求处理)
//                if (!$user) {
//                    $user = User::create([
//                        //这里把微信头像缓存到本地,与其他头像保持统一,默认储存驱动为 public
//                        'avatar' => '/files' . UploadController::flow(file_get_contents($oauthUser->getAvatar()), 'avatars'),
//                        'weixin_openid' => $openid,
//                    ]);
//                }
        }
    }

    public function store(AuthorizationRequest $request)
    {
        $username = $request->username;

        filter_var($username, FILTER_VALIDATE_EMAIL) ?
            $credentials['email'] = $username :
            $credentials['phone'] = $username;

        $credentials['password'] = $request->password;

        if (!$token = \Auth::guard('api')->attempt($credentials, 1)) {
            return error('用户名或密码错误');
        }


        return $this->respondWithToken($token);
    }

    protected function sso($token)
    {
        //把数据库原token加入黑名单,实现单点登录
        $oldToken = auth('api')->setToken($token)->user()->token;
        if (!empty($oldToken) && JWTAuth::setToken($oldToken)->check()) {
            JWTAuth::setToken($oldToken)->invalidate();
        }

        auth('api')->user()->token = $token;
        auth('api')->user()->save();
    }

    protected function respondWithToken($token)
    {
        $this->sso($token);
        return success('登录成功', [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function update()
    {
        $token = auth('api')->refresh();
        return $this->respondWithToken($token);
    }

    public function destroy()
    {
        auth('api')->logout();
        return success('退出成功');
    }

    /**
     * 修改密码
     * @param Request $request
     * @return mixed
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => [
                'required', 'alpha_dash', 'min:6',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, auth()->user()->password)) {
                        return $fail('原密码错误');
                    }
                }
            ],
            'new_password' => 'required|alpha_dash|min:6',
        ], [], [
            'old_password' => '原密码',
            'new_password' => '新密码',
        ]);

        if ($validator->fails()) {
            return error($validator->errors()->first());
        }

        auth()->user()->password = Hash::make($request->new_password);
        $result = auth()->user()->save();

        if ($request) {
            return success('修改成功', $result);
        } else {
            return error('修改失败');
        }
    }

    /**
     * 忘记密码
     * @param Request $request
     * @return mixed
     */
    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|alpha_dash|min:6',
            'verification_key' => 'required|string',
            'verification_code' => 'required|string',
        ], [], [
            'verification_key' => '短信验证码 key',
            'verification_code' => '短信验证码',
        ]);

        if ($validator->fails()) {
            return error($validator->errors()->first());
        }

        $verifyData = \Cache::get($request->verification_key);

        if (!$verifyData) {
            return error('验证码已失效');
        }

        if (!hash_equals($verifyData['code'], $request->verification_code)) {
            return error('验证码错误');
        }

        $user = User::where('phone', $verifyData['phone'])->first();

        $user->password = Hash::make($request->password);
        $result = $user->save();

        if ($result) {
            // 清除验证码缓存
            \Cache::forget($request->verification_key);

            return success('修改成功');
        } else {
            return error('修改失败');
        }
    }
}
