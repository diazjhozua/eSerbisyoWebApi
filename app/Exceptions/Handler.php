<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (Exception $exception, $request) {
            if( $request->is('api/*')){
                if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                    $model = strtolower(class_basename($exception->getModel()));

                    return response()->json([
                        'success' => false,
                        'message' => $model.' not found'
                    ], 404);
                }
                if ($exception instanceof NotFoundHttpException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Resource not found'
                    ], 404);
                }
            } else if ('/*') {
                if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                    $model = strtolower(class_basename($exception->getModel()));

                    return response()->json([
                        'success' => false,
                        'message' => $model.' not found (It must be deleted by administrator. Please refresh the page)'
                    ]);
                }
                if ($exception instanceof NotFoundHttpException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Resource not found'
                    ]);
                }
            }
        });
    }

}
