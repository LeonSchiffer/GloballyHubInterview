<?php

namespace App\DTO;

use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Reader\Csv as ReaderCsv;

abstract class ExcelDto
{
    abstract public static function getFilePath(): string;
    abstract public static function fromRow(array $row);

    public static function all(): Collection
    {
        $data = Collection::make();
        $reader = new ReaderCsv();
        $spreadsheet = $reader->load(static::getFilePath());
        $clients = $spreadsheet->getActiveSheet()->toArray();
        foreach ($clients as $row) {
            if (is_null($row[0]))
                continue;
            $data->push(static::fromRow($row));
        }
        return $data;
    }
}
