<?php

namespace App\Exceptions\Excel;

use Exception;

class InvalidExcelDriver extends Exception
{
    public function render()
    {
        abort(403, "Invalid excel driver");
    }
}
