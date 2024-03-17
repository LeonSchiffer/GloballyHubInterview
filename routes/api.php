<?php

use App\Http\Controllers\Api\Client\ClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource("client", ClientController::class)->only(["index", "store"]);
