<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ClientController extends Controller
{
    public function index()
    {
        return Inertia::render("Client/Index");
    }

    public function create()
    {
        return Inertia::render("Client/Create");
    }
}
