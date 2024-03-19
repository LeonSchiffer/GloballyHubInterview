<?php

namespace App\Services;

use App\DTO\ExcelDto;
use App\Exceptions\Excel\InvalidExcelDriver;
use App\Repositories\CsvRepository;
use App\Repositories\XlsxRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;
use PhpOffice\PhpSpreadsheet\Writer\BaseWriter;

class WorksheetService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {

    }

    public function insertRow(BaseReader $reader, array $data, string $file_path): Spreadsheet
    {

        $spreadsheet = $reader->load($file_path);
        $sheet = $spreadsheet->getActiveSheet();
        $highest_row = $sheet->getHighestRow();
        $current_row = ($highest_row == 1) ? $highest_row : ++$highest_row;
        $sheet->insertNewRowBefore($current_row);
        foreach ($data as $coordinate => $value) {
            $sheet->setCellValue($coordinate . $current_row, $value);
        }
        return $spreadsheet;
    }

    private function getStoragePath($file_name)
    {
        return storage_path("app/$file_name");
    }

    public static function migrate(array $excel_dto_classes)
    {
        foreach ($excel_dto_classes as $excel_dto_class)
        {
            /** @var ExcelDto $excel_dto_class */
            $excel_dto_class::migrateFile();
        }
    }

    public static function getRepositoryNameByDriver()
    {
        return match (config("app.excel_driver")) {
            "csv" => CsvRepository::class,
            "xlsx" => XlsxRepository::class,
            default => throw new InvalidExcelDriver
        };
    }
}
