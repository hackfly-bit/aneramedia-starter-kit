<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * Return a success response
     */
    protected function success($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return ApiResponse::success($data, $message, $code);
    }

    /**
     * Return an error response
     */
    protected function error(string $message = 'Error', int $code = 400, $data = null): JsonResponse
    {
        return ApiResponse::error($message, $code, $data);
    }

    /**
     * Return a validation error response
     */
    protected function validationError($errors, string $message = 'Validation failed'): JsonResponse
    {
        return ApiResponse::validationError($errors, $message);
    }

    /**
     * Return a not found response
     */
    protected function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return ApiResponse::notFound($message);
    }

    /**
     * Return an unauthorized response
     */
    protected function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return ApiResponse::unauthorized($message);
    }

    /**
     * Return a forbidden response
     */
    protected function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return ApiResponse::forbidden($message);
    }

    /**
     * Return a server error response
     */
    protected function serverError(string $message = 'Internal server error'): JsonResponse
    {
        return ApiResponse::serverError($message);
    }
}