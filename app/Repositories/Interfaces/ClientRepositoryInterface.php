<?php

namespace App\Repositories\Interfaces;

use App\DTO\ClientDto;

interface ClientRepositoryInterface
{
    /**
     * Store clients data in the excel file
     * @param ClientDto $client
     */
    public function store(ClientDto $client);
}
