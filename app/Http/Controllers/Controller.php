<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    /**
     * Return error JSON response.
     *
     * @param array|null $data
     * @param int|null $status
     * @param array|null $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse(?array $data = null, ?int $status = null, ?array $headers = null): JsonResponse
    {
        return \response()->json($data ?? [], $status ?? 400, $headers ?? []);
    }

    /**
     * Return successful JSON response.
     *
     * @param array|null $data
     * @param array|null $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successfulResponse(?array $data = null, ?array $headers = null): JsonResponse
    {
        return \response()->json($data ?? [], 200, $headers ?? []);
    }
}
