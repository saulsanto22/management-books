<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success(mixed $data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        $response = [
            'status' => 'success',
            'message' => $message,
        ];

        if (! is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    public static function error(string $message = 'Error', int $code = 400, mixed $errors = null): JsonResponse
    {
        $response = [
            'status' => 'error',
            'message' => $message,
        ];

        if (! is_null($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    public static function paginated($collection, string $message = 'Success'): JsonResponse
{
    return response()->json([
        'status' => 'success',
        'message' => $message,
        'data' => $collection->items(),
        'meta' => [
            'current_page' => $collection->currentPage(),
            'last_page' => $collection->lastPage(),
            'per_page' => $collection->perPage(),
            'total' => $collection->total(),
        ],
    ], 200);
}

}
