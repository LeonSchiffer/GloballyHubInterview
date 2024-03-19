<?php

namespace App\DTO;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Reader\Csv as ReaderCsv;

abstract class ExcelDto
{
    abstract public static function getFilePath(): string;
    abstract public static function fromRow(array $row);

    public static function all(int $limit = null, int $page = null): LengthAwarePaginator
    {
        $limit = $limit ?: 15;
        $page = $page ?: 1;
        $page = is_null($page) ? request()->query("page", 1) : $page;
        $data = Collection::make();
        $reader = new ReaderCsv();
        $spreadsheet = $reader->load(static::getFilePath());
        $active_sheet = $spreadsheet->getActiveSheet();
        $clients = $active_sheet->toArray();
        $clients = static::paginate($clients, $limit, $page);
        foreach ($clients as $row) {
            if (is_null($row[0]))
                continue;
            $data->push(static::fromRow($row));
        }
        return new LengthAwarePaginator(
            $data,
            $active_sheet->getHighestRow(),
            $limit,
            $page,
            [
                "path" => url()->current() . "?limit=$limit"
            ]
        );
    }

    private static function paginate(array $rows,  int $limit, int $page)
    {
        return array_slice(
            $rows,
            ($limit * $page) - $limit,
            $limit
        );
    }
}
