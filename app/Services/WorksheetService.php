<?php

namespace App\Services;

use App\Models\BaseExcelModel;
use App\Exceptions\Excel\InvalidExcelDriverException;
use App\Repositories\CsvRepository;
use App\Repositories\XlsxRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;

/**
 *
 */
class WorksheetService
{
    public function __construct()
    {

    }

    /**
     * Add a new row to the excel file
     * @param BaseReader $reader The base reader file which is implemented by Xlsx and Csv reader
     * @param array $data The row to be added to the excel file
     * @param string $file_path The full path of the file where the data is to be inserted
     */
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

    /**
     * Migrates all the instance of BaseExcelModels it finds
     * @see \App\Providers\AppServiceProvider
     * @param array $excel_dto_classes
     */
    public static function migrate(array $excel_dto_classes)
    {
        foreach ($excel_dto_classes as $excel_dto_class)
        {
            /** @var BaseExcelModel $excel_dto_class */
            $excel_dto_class::migrateFile();
        }
    }

    /**
     * Get respective class according to driver in .env file
     * @return string|InvalidExcelDriverException
     */
    public static function getRepositoryNameByDriver()
    {
        return match (config("app.excel_driver")) {
            "csv" => CsvRepository::class,
            "xlsx" => XlsxRepository::class,
            default => throw new InvalidExcelDriverException
        };
    }
}
