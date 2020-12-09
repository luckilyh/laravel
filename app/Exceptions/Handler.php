<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            return response('抱歉，访问页面不存在！', 404);
        }

        if ($exception->getMessage() == 'The token has been blacklisted'){
            return response('抱歉，该token已被拉入黑名单！', 403);
        }

        if ($exception->getMessage() == 'Token has expired and can no longer be refreshed'){
            return response('令牌已过期，不能再刷新！', 423);
        }

        if ($exception->getMessage() == 'Too Many Attempts.'){
            return response('请求太频繁，请稍后再试！', 429);
        }

        return parent::render($request, $exception);
    }
}
