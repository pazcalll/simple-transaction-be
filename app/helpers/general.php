<?php

if (!function_exists('responseJson')) {
    function responseJson($data = null, $message = 'OK', $status = 200)
    {
        $data = [
            'code' => '20000',
            'message' => $message
        ];

        if ($data) $data['data'] = $data;

        return response()->json($data, $status);
    }
}