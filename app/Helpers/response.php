<?php

function responseError($message = "", $status = 500) {
    return response()->json([
        "message" => $message
    ], $status);
}
