<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Log;
use Psy\Exception\TypeErrorException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use TypeError;

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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // if( $request->is('api/*')){
        //     if ($exception instanceof ModelNotFoundException) {
        //         return response()->json([
        //             'success'=> false,
        //             'message' => $exception->getModel().'not found (It must be deleted by administrator. Please refresh the page)'
        //         ], 404);
        //     }
        // }

        if ($exception instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($exception->getModel()));
            if( $request->is('api/*') || $request->ajax()){
                return response()->json([
                    'success'=> false,
                    'message' => ucwords($model).' not found (It must be deleted by administrator. Please refresh the page)'
                ], 404);
            }
        }
        if ($exception instanceof NotFoundHttpException) {
            if( $request->is('api/*') || $request->ajax()){
                return response()->json([
                    'success' => false,
                    'message' => 'Resource not found'
                ]);
            }
        }

        if( $request->is('api/*') || $request->ajax()){
            return response()->json([
                'success' => false,
                'message' => 'Resource not found'
            ]);
        }


        return parent::render($request, $exception);
    }
}
