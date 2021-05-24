<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthenticationException){
            return response('未登录或登录已失效！', 401);
        }

        if ($exception instanceof TokenBlacklistedException) {
            return response('抱歉，该token已被拉入黑名单！', 403);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response('抱歉，访问页面不存在！', 404);
        }

        if ($exception instanceof TokenExpiredException) {
            return response('令牌已过期，不能再刷新！', 423);
        }

        if ($exception instanceof ThrottleRequestsException) {
            return response('请求太频繁，请稍后再试！', 429);
        }

        if ($exception instanceof \Exception) {
            // 自定义验证器抛出异常
            if ($exception->getFile() == app_path('Helpers/function.php')){
                return response()->json([
                    'code' => 404,
                    'msg' => $exception->getMessage(),
                    'data' => [],
                ]);
            }
            return response()->json([
                'code' => 500,
                'msg' => $exception->getMessage(),
                'data' => [
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ],
            ]);
        }

        return parent::render($request, $exception);
    }
}
