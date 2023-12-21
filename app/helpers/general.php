<?php

if (!function_exists('responseJson')) {
    function responseJson($data, $message = 'OK', $status = 200)
    {
        return response()->json([
            'code' => '20000',
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}