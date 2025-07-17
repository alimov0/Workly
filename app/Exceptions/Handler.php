<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        // Custom error handling
        $this->renderable(function (Throwable $e, $request) {

            if ($e instanceof ValidationException) {
                return response()->json([
                    'message' => 'Validatsiya xatoligi.',
                    'errors' => $e->errors()
                ], 422);
            }

            if ($e instanceof NotFoundHttpException) {
                return response()->json([
                    'message' => 'Resurs topilmadi.'
                ], 404);
            }

            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'message' => 'Avtorizatsiyadan o‘ting.'
                ], 401);
            }

            return response()->json([
                'message' => 'Server xatosi.',
                'error' => $e->getMessage()
            ], 500);
        });
    }
}
