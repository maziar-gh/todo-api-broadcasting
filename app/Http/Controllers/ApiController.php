<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class ApiController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function response($status = 500, $data = []){

        $message = match ($status) {
            $status => __('Api.'.$status),
            default => 'Unknown error.',
        };

        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];

        return response($response, $status);
    }
}
