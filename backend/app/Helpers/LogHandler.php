<?php

use Illuminate\Support\Facades\Log;


function logInfo(string $logMessage, mixed $data = null)
{
    Log::info($logMessage, [
        'data' => $data,
    ]);
}

function logNotice(string $logMessage, array $data = [])
{
    Log::notice($logMessage, [
        'data' => $data,
    ]);
}

function logAlert(string $logMessage, mixed $data = null)
{
    Log::alert($logMessage, [
        'data' => $data,
    ]);
}

function logWarning(string $logMessage, mixed $errors, array $data = [])
{
    Log::warning($logMessage, [
        'errors' => $errors,
        'request_data' => $data,
    ]);
}

function logError(\Exception $e, string $logMessage = 'An error occurred. Please try again later',  mixed $data = null)
{
    Log::error($logMessage, [
        'error_message' => $e->getMessage(),
        'data' => $data,
        // 'trace' => $e->getTraceAsString(),
    ]);
}
