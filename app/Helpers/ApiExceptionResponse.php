<?php

namespace App\Helpers;

use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class ApiExceptionResponse
{
    use ApiResponse;

    public static function error(
        string $message,
        int $code = 400,
        mixed $errors = null
    ): JsonResponse {
        return (new self)->errorResponse($message, $code, $errors);
    }

    public static function validation(
        array $errors,
        string $message = 'Validation failed'
    ): JsonResponse {
        return (new self)->validationErrorResponse($errors, $message);
    }
}
