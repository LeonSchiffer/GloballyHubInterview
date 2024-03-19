<?php

function responseError($message = "", $status = 500) {
    return response()->json([
        "message" => $message
    ], $status);
}

function responseSuccess($data = [], $message = "", $status = 200)
{
    return response()->json([
        "data" => $data,
        "message" => $message
    ], $status);
}
