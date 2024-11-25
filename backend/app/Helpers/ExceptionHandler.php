<?php

use Symfony\Component\HttpFoundation\Response;

function handleException(\Exception $e,  string $message = 'An error occurred. Please try again later', int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR)
{
    return response()->json([
        'status' => false,
        'message' => $message,
        'error_message' => $e->getMessage(),
        // 'trace' => $e->getTraceAsString(),
    ], $statusCode);
}
