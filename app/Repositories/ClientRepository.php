<?php

namespace App\Repositories;

use App\Models\Client;
use App\Repositories\Interfaces\ClientRepositoryInterface;
use App\Repositories\Interfaces\SpreadsheetInterface;

class ClientRepository implements ClientRepositoryInterface
{
    public function __construct(private SpreadsheetInterface $spreadsheet)
    {

    }

    public function store(Client $client)
    {
        $this->spreadsheet->addRow($client->toCsv(), Client::getFileName());
    }
}
