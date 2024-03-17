<?php

namespace App\Repositories;

use App\DTO\ClientDto;
use App\Repositories\Interfaces\ClientRepositoryInterface;
use App\Repositories\Interfaces\SpreadsheetInterface;

class ClientRepository implements ClientRepositoryInterface
{
    public function __construct(private SpreadsheetInterface $spreadsheet)
    {

    }

    public function store(ClientDto $client)
    {
        $this->spreadsheet->addRow($client->toCsv(), ClientDto::getFilePath());
    }
}
