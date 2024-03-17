<?php

namespace App\Repositories\Interfaces;

interface SpreadsheetInterface
{
    public function addRow(array $data, string $file_path);
}
