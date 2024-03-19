<?php

namespace App\Exceptions\Excel;

use Exception;

class InvalidExcelDriverException extends Exception
{
    /**
     * For handling invalid excel driver in the .env
     */
    public function render()
    {
        abort(403, "Invalid excel driver");
    }
}
