<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Models\Client;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use App\Http\Requests\Api\Client\StoreClientRequest;
use App\Http\Resources\ClientResource;
use App\Repositories\Interfaces\ClientRepositoryInterface;
use Exception;
use PhpOffice\PhpSpreadsheet\Writer\Csv as WriterCsv;

class ClientController extends Controller
{
    public function __construct(private ClientRepositoryInterface $client)
    {

    }
    /**
     * Returns paginated client list
     */
    public function index()
    {
        return  Client::all(
            request()->query("limit", 15),
            request()->query("page", 1)
        );
    }

    /**
     * Stores a new client
     */
    public function store(StoreClientRequest $request)
    {
        try {
            $clientDto = Client::fromArray($request->validated());
            $this->client->store($clientDto);
            return responseSuccess(message: "Client successfully saved!", status: 201);
        } catch (Exception $ex) {
            return responseError($ex->getMessage());
        }
    }
}
