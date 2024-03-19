<?php

namespace App\Repositories\Interfaces;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

interface SpreadsheetInterface
{
    /**
     * Adds a new row to the excel file
     * @param array $data The row to add
     * @param string $file_path The file to add the row to
     */
    public function addRow(array $data, string $file_path);

    /**
     * Gets the currently active worksheet
     * @param string $file_path The file to get the worksheet from
     * @return Worksheet
     */
    public function getWorksheet(string $file_path): Worksheet;

    /**
     * Get the full file path depending upon the excel driver in .env
     * @return string
     */
    public function getFullFilePath($file_name): string;

    /**
     * The logic for creating the excel file to store data depending upon the excel driver in .env
     */
    public function createMigrationFile(string $file_path): bool;
}
