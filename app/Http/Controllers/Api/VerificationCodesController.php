<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VerificationCode2Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;
use App\Http\Requests\Api\VerificationCodeRequest;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        $phone = $request->phone;

        if (!app()->environment('production')) {
            $code = '1234';
        } else {
            // 生成4位随机数，左侧补0
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);

            try {
                $result = $easySms->send($phone, [
                    'template' => config('easysms.gateways.aliyun.templates.general'),
                    'data' => [
                        'code' => $code
                    ],
                ]);
            } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
                $message = $exception->getException('aliyun')->getMessage();
                return exception($message ?: '短信发送异常');
            }
        }

        $key = 'verificationCode_' . Str::random(15);
        $expiredAt = now()->addMinutes(5);
        // 缓存验证码 5 分钟过期。
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);

        return success('短信发送成功', [
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ]);
    }

    //带有图片验证码
    public function store2(VerificationCode2Request $request, EasySms $easySms)
    {
        $captchaData = \Cache::get($request->captcha_key);

        if (!$captchaData) {
            return error('图片验证码已失效');
        }

        if (!hash_equals(strtolower($captchaData['code']), strtolower($request->captcha_code))) {
            // 验证错误就清除缓存
            \Cache::forget($request->captcha_key);
            return error('验证码错误');
        }

        $phone = $captchaData['phone'];

        if (!app()->environment('production')) {
            $code = '1234';
        } else {
            // 生成4位随机数，左侧补0
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);

            try {
                $result = $easySms->send($phone, [
                    'template' => config('easysms.gateways.aliyun.templates.general'),
                    'data' => [
                        'code' => $code
                    ],
                ]);
            } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
                $message = $exception->getException('aliyun')->getMessage();
                return exception($message ?: '短信发送异常');
            }
        }

        $key = 'verificationCode_' . Str::random(15);
        $expiredAt = now()->addMinutes(5);
        // 缓存验证码 5分钟过期。
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);
        // 清除图片验证码缓存
        \Cache::forget($request->captcha_key);

        return success('短信发送成功', [
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ]);
    }

    //发送邮箱验证
    public function email(Request $request)
    {
        $data = verify($request, [
            'email' => 'required|email:rfc,dns|unique:App\Models\User,email',
        ], [], []);

        $key = 'verificationCode_' . Str::random(15);
        $expiredAt = now()->addMinutes(60 * 24);
        $user = auth()->user();
        // 缓存一天过期。
        \Cache::put($key, ['email' => $data['email'], 'user_id' => $user->id], $expiredAt);
        Mail::send('emails.authentication',
            [
                'url' => config('app.url') . "/validation_email?verification_key={$key}",
                'user' => $user
            ],
            function ($message) use ($data) {
                $message->to($data['email'])->subject('注册');
            });

        return success('邮件发送成功');
    }

    public function storeEmail(Request $request)
    {
        $emailData = \Cache::get($request->verification_key);
        if (!$emailData) {
            $msg = '验证失效，请重新发送邮件！';
        }else{
            $user = User::find($emailData['user_id']);
            if (empty($user->email_verified_at)) {
                $user->email = $emailData['email'];
                $user->email_verified_at = Carbon::now();
                $user->save();
                $msg = '邮箱认证成功！';
            }else{
                $msg = '邮箱已认证！';
            }
        }


        return view('emails.authentication_success',[
            'msg' => $msg
        ]);
    }
}
