<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\DTO\ClientDto;
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
     * Display a listing of the resource.
     */
    public function index()
    {
        return  ClientDto::all(
            request()->query("limit", 15),
            request()->query("page", 1)
        );
    }
    // public function index()
    // {
    //     $reader = new Csv();
    //     $spreadsheet = $reader->load(storage_path("/app/client.csv"));
    //     $sheet = $spreadsheet->getActiveSheet();
    //     $highest_row = $sheet->getHighestRow();
    //     $sheet->insertNewRowBefore($highest_row+1);
    //     $sheet->setCellValue("A" . $highest_row + 1, "ABC7");
    //     // $sheet->setCellValue("A1", "ABC1");
    //     $writer = new WriterCsv($spreadsheet);
    //     $writer->save(storage_path("app/client.csv"));
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        try {
            $clientDto = ClientDto::fromArray($request->validated());
            $this->client->store($clientDto);
            return responseSuccess(message: "Client successfully saved!", status: 201);
        } catch (Exception $ex) {
            return responseError($ex->getMessage());
        }
    }
}
