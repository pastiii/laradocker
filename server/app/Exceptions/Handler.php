<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    protected $error_msg    = CHINESE_MSG[RETURN_ERROR];
    protected $error_status = RETURN_ERROR;

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
     * @param \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ExampleException) {
            return $this->handle($request, $exception);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return response(['status' => 405, 'data' => NULL, 'msg' => 'Method Not Allowed']);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response(['status' => 404, 'data' => NULL, 'msg' => 'Not Found']);
        }

        if ($exception instanceof ValidationException) {
            $error_info = array_slice($exception->errors(), 0, 1, false);
            $msg        = array_column($error_info, 0);

            return response(['status' => RETURN_ERROR, 'data' => NULL, 'msg' => $msg['0']]);
        }


        return parent::render($request, $exception);
    }

    /**
     * 自定义异常抛出
     * @param $request
     * @param Exception $e
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Exception $e)
    {
        // 只处理自定义的ExampleException异常
        if ($e instanceof ExampleException) {
            $result = [
                'status' => $e->error_status,
                'data'   => NULL,
                'msg'    => $e->error_msg,
            ];
            return response()->json($result);
        }
        return parent::render($request, $e);
    }
}
