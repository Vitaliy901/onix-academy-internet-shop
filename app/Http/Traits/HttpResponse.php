<?php

namespace App\Http\Traits;

use App\Enums\Status;

trait HttpResponse
{
    public function success(
        $data,
        int $code = 200,
        string $message = 'Request was successful',
    ) {
        return response()->json([
            'status' => Status::TRUE,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function error(
        $data,
        int $code = 401,
        string $message = 'Error has occurred',
    ) {
        return response()->json([
            'status' => Status::FALSE,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
