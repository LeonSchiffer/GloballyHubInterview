<?php

namespace App\Repositories;

use PhpOffice\PhpSpreadsheet\Reader\Csv as ReaderCsv;
use PhpOffice\PhpSpreadsheet\Writer\Csv as WriterCsv;
use App\Repositories\Interfaces\SpreadsheetInterface;

class CsvRepository implements SpreadsheetInterface
{
    public function addRow(array $data, string $file_path): bool
    {
        $reader = new ReaderCsv();
        $spreadsheet = $reader->load($file_path);
        $sheet = $spreadsheet->getActiveSheet();
        $highest_row = $sheet->getHighestRow();
        $current_row = ($highest_row == 1) ? $highest_row : ++$highest_row;
        $sheet->insertNewRowBefore($current_row);
        foreach ($data as $coordinate => $value) {
            $sheet->setCellValue($coordinate . $current_row, $value);
        }
        // $sheet->setCellValue("A1", "ABC1");
        $writer = new WriterCsv($spreadsheet);
        $writer->save($file_path);
        return true;
    }
}
