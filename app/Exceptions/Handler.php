<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Responses\ApiResponse;
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // Always return JSON for API routes
        if ($request->is('api/*') || $request->expectsJson()) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle API exceptions and return JSON responses
     */
    private function handleApiException(Request $request, Throwable $e)
    {
        // Validation exceptions
        if ($e instanceof ValidationException) {
            return ApiResponse::validationError($e->errors(), 'Validation failed');
        }

        // Not found exceptions
        if ($e instanceof NotFoundHttpException) {
            return ApiResponse::notFound('Endpoint not found');
        }

        // Method not allowed exceptions
        if ($e instanceof MethodNotAllowedHttpException) {
            return ApiResponse::error('Method not allowed', 405);
        }

        // HTTP exceptions
        if ($e instanceof HttpException) {
            return ApiResponse::error($e->getMessage() ?: 'HTTP Error', $e->getStatusCode());
        }

        // General exceptions
        if (config('app.debug')) {
            return ApiResponse::serverError($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        }

        return ApiResponse::serverError('Internal server error');
    }
}