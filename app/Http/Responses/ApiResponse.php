<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * Return a success response
     */
    public static function success($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => $message,
            'code' => $code
        ], $code);
    }

    /**
     * Return an error response
     */
    public static function error(string $message = 'Error', int $code = 400, $data = null): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'data' => $data,
            'message' => $message,
            'code' => $code
        ], $code);
    }

    /**
     * Return a validation error response
     */
    public static function validationError($errors, string $message = 'Validation failed', int $code = 422): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'data' => [
                'errors' => $errors
            ],
            'message' => $message,
            'code' => $code
        ], $code);
    }

    /**
     * Return a not found response
     */
    public static function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return self::error($message, 404);
    }

    /**
     * Return an unauthorized response
     */
    public static function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return self::error($message, 401);
    }

    /**
     * Return a forbidden response
     */
    public static function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return self::error($message, 403);
    }

    /**
     * Return a server error response
     */
    public static function serverError(string $message = 'Internal server error'): JsonResponse
    {
        return self::error($message, 500);
    }
}