<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait HandleJsonResponse
{
    public function success(array $data = [], string $message = 'success', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function error(string $message = 'error', int $code = 429): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $code);
    }
}
