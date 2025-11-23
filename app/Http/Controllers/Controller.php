<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    protected function responseSuccess(array $data = [], int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ], $statusCode);
    }

    protected function responseError(array $data = [], int $statusCode = 500): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'error' =>  $data,
        ], $statusCode);
    }
}
