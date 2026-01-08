<?php

use App\Helpers\ApiExceptionResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        /**
         * Force JSON for API
         */
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) =>
            $request->is('api/*') || $request->expectsJson()
        );

        /**
         * Main API Exception Renderer
         */
        $exceptions->render(function (Throwable $e, Request $request) {

            if (!$request->is('api/*') && !$request->expectsJson()) {
                return null;
            }

            return match (true) {

                $e instanceof ValidationException =>
                ApiExceptionResponse::validation(
                    $e->errors(),
                    'Validation failed'
                ),

                $e instanceof ModelNotFoundException,
                $e instanceof NotFoundHttpException =>
                ApiExceptionResponse::error(
                    'Resource not found',
                    404
                ),

                $e instanceof AuthenticationException =>
                ApiExceptionResponse::error(
                    'Unauthenticated',
                    401
                ),

                $e instanceof AuthorizationException =>
                ApiExceptionResponse::error(
                    'Unauthorized action',
                    403
                ),

                $e instanceof MethodNotAllowedHttpException =>
                ApiExceptionResponse::error(
                    'Method not allowed',
                    405
                ),

                method_exists($e, 'getStatusCode') =>
                ApiExceptionResponse::error(
                    $e->getMessage(),
                    $e->getStatusCode()
                ),

                default =>
                ApiExceptionResponse::error(
                    config('app.debug')
                    ? $e->getMessage()
                    : 'Internal server error',
                    500
                ),
            };
        });
    })->create();
