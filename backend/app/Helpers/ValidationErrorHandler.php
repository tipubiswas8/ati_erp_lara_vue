<?php

use Symfony\Component\HttpFoundation\Response;

function handleValidationError(mixed $validation_errors = null,  string $message = 'Bad Request, please provide valid data', int $statusCode = Response::HTTP_BAD_REQUEST)
{
    // Return immediately if validation fails
    return response()->json([
        'status' => false,
        'validation_errors' => $validation_errors,
        'message' => $message,
    ], $statusCode);
}
