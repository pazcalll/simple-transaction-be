<?php

if (!function_exists('responseJson')) {
    function responseJson($data = null, $message = 'OK', $status = 200)
    {
        $response = [
            'code' => $status,
            'message' => $message
        ];

        if ($data) $response['data'] = $data;

        return response()->json($response, $status);
    }
}