<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (Throwable $e) {
            if (request()->routeIs('api.*')) {
                if ($e instanceof ValidationException) {
                    return response()->json(['errors' => collect($e->errors())], Response::HTTP_UNPROCESSABLE_ENTITY); // Status: 422
                }

                if ($e instanceof AuthenticationException) {
                    return response()->json(['message' => $e->getMessage()], Response::HTTP_UNAUTHORIZED); // Status: 401
                }

                if ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException) {
                    return response()->json(['message' => 'Not found!'], Response::HTTP_NOT_FOUND); // Status: 404
                }

                return response()->json(['message' => 'Server error!'], Response::HTTP_INTERNAL_SERVER_ERROR); // Status: 500
            }
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
