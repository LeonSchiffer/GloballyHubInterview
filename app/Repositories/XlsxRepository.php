<?php

namespace App\Repositories;

use App\Services\WorksheetService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Repositories\Interfaces\SpreadsheetInterface;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as WriterXlsx;

class XlsxRepository implements SpreadsheetInterface
{
    public function __construct(private WorksheetService $worksheet_service)
    {
    }

    public function addRow(array $data, string $file_name): bool
    {
        $reader = new ReaderXlsx();
        $file_path = $this->getFullFilePath($file_name);
        $spreadsheet = $this->worksheet_service->insertRow($reader, $data, $file_path);
        // $sheet->setCellValue("A1", "ABC1");
        $writer = new WriterXlsx($spreadsheet);
        $writer->save($file_path);
        return true;
    }

    public function getWorksheet(string $file_name): Worksheet
    {
        $reader = new ReaderXlsx();
        $spreadsheet = $reader->load($this->getFullFilePath($file_name));
        $active_sheet = $spreadsheet->getActiveSheet();
        return $active_sheet;
    }

    public function getFullFilePath($file_name): string
    {
        $full_path = storage_path("/app/") . "$file_name" . ".xlsx";
        return $full_path;
    }

    public function createMigrationFile($file_path): bool
    {
        if (file_exists($file_path))
            return false;
        $spread_sheet = new Spreadsheet();
        $writer = new WriterXlsx($spread_sheet);
        $writer->save($file_path);
        return true;
    }
}
