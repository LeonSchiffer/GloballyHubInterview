<?php

namespace App\Repositories\Interfaces;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

interface SpreadsheetInterface
{
    public function addRow(array $data, string $file_path);
    public function getWorksheet(string $file_path): Worksheet;
    public function getFullFilePath($file_name): string;
    public function createMigrationFile(string $file_path): bool;
}
