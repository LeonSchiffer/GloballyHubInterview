<?php

/**
 * For send a error json response with a specific format
 */
function responseError($message = "", $status = 500)
{
    return response()->json([
        "message" => $message
    ], $status);
}

/**
 * For send a success json response with a specific format
 */
function responseSuccess($data = [], $message = "", $status = 200)
{
    return response()->json([
        "data" => $data,
        "message" => $message
    ], $status);
}
