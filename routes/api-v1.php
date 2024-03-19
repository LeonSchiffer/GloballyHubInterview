<?php

use App\Http\Controllers\Api\V1\Client\ClientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource("clients", ClientController::class)->only(["index", "store"]);
