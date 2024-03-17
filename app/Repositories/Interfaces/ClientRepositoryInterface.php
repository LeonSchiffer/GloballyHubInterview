<?php

namespace App\Repositories\Interfaces;

use App\DTO\ClientDto;

interface ClientRepositoryInterface
{
    public function store(ClientDto $client);
}
