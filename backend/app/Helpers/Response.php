<?php

use Symfony\Component\HttpFoundation\Response;

function responseSuccess(string $message = 'Action performed successfully!', mixed $data = null, int $statusCode = Response::HTTP_OK)
{
    return response()->json([
        'status' => true,
        'message' => $message,
        'data' => $data,
    ], $statusCode);
}
function responseFailed(string $message = 'Action performed failed!', mixed $data = null, int $statusCode = 422)
{
    return response()->json([
        'status' => false,
        'error_message' => $message,
        'data' => $data,
    ], $statusCode);
}
function responseNotFound(string $message = 'Not Found!', mixed $data = null, int $statusCode = Response::HTTP_NOT_FOUND)
{
    return response()->json([
        'status' => false,
        'error_message' => $message,
        'data' => $data,
    ], $statusCode);
}
