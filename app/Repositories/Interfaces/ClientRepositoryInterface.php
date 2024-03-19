<?php

namespace App\Repositories\Interfaces;

use App\Models\Client;

interface ClientRepositoryInterface
{
    /**
     * Store clients data in the excel file
     * @param Client $client
     */
    public function store(Client $client);
}
