<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function jsonResponse(
        string $message,
        mixed $data = null,
        int $status = Response::HTTP_OK
    ): JsonResponse {
        $response['message'] = $message;

        if (! empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $status);
    }
}
