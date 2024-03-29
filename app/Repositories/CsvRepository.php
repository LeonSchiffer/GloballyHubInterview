<?php

namespace App\Repositories;

use App\Services\WorksheetService;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Repositories\Interfaces\SpreadsheetInterface;
use PhpOffice\PhpSpreadsheet\Reader\Csv as ReaderCsv;
use PhpOffice\PhpSpreadsheet\Writer\Csv as WriterCsv;

class CsvRepository implements SpreadsheetInterface
{
    public function __construct(private WorksheetService $worksheet_service)
    {
    }

    public function addRow(array $data, string $file_name): bool
    {
        $reader = new ReaderCsv();
        $file_path = $this->getFullFilePath($file_name);
        $spreadsheet = $this->worksheet_service->insertRow($reader, $data, $file_path);
        // $sheet->setCellValue("A1", "ABC1");
        $writer = new WriterCsv($spreadsheet);
        $writer->save($file_path);
        return true;
    }

    public function getWorksheet(string $file_name): Worksheet
    {
        $reader = new ReaderCsv();
        $spreadsheet = $reader->load($this->getFullFilePath($file_name));
        $active_sheet = $spreadsheet->getActiveSheet();
        return $active_sheet;
    }

    public function getFullFilePath($file_name): string
    {
        $full_path = storage_path("/app/") . "$file_name" . ".csv";
        return $full_path;
    }

    public function createMigrationFile($file_path): bool
    {
        if (file_exists($file_path))
            return false;
        $spread_sheet = new Spreadsheet();
        $writer = new WriterCsv($spread_sheet);
        $writer->save($file_path);
        return true;
    }
}
