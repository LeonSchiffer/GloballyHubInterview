<?php

use App\Http\Controllers\Client\ClientController;
use Illuminate\Support\Facades\Route;


Route::redirect("/", "/clients/create");
Route::resource("clients", ClientController::class)->only(["index", "create"]);

