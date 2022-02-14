<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BaseController extends Controller
{
    public function sendResponse($message, $data){
        $response = [
            'status' => true,
            'message' => $message,
            'data'    => $data
        ];

        return response()->json($response, 200)->setStatusCode(Response::HTTP_OK);
    }

    public function sendError($error, $code = 404)
    {
        $response = [
            'status' => false,
            'message' => $error,
            'error_code' => $code
        ];

        return response()->json($response, $code);
    }
}
