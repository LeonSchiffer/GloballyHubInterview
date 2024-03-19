<?php

use App\Http\Controllers\Api\V1\Client\ClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource("clients", ClientController::class)->only(["index", "store"]);
Route::get("test", function () {
    return "test";
});
