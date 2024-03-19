<?php

namespace App\DTO;

use App\Repositories\Interfaces\SpreadsheetInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

abstract class ExcelDto
{
    abstract public static function getFilePath(): string;
    abstract public static function fromRow(array $row);

    public static function all(int $limit = null, int $page = null): LengthAwarePaginator
    {

        $spreadsheet_repository = app()->make(SpreadsheetInterface::class);
        $worksheet = $spreadsheet_repository->getWorksheet(static::getFilePath());
        $clients = $worksheet->toArray();
        $data = static::paginateData($clients, $limit, $page);
        return new LengthAwarePaginator(
            $data,
            $worksheet->getHighestRow(),
            $limit,
            $page,
            [
                "path" => url()->current() . "?limit=$limit"
            ]
        );
    }

    private static function sliceForPagination(array $rows,  int $limit, int $page)
    {
        return array_slice(
            $rows,
            ($limit * $page) - $limit,
            $limit
        );
    }

    private static function paginateData($data, $limit, $page)
    {
        $limit = $limit ?: 15;
        $page = $page ?: 1;
        $page = is_null($page) ? request()->query("page", 1) : $page;
        $paginated_data = Collection::make();
        $clients = static::sliceForPagination($data, $limit, $page);
        foreach ($clients as $row) {
            if (is_null($row[0]))
                continue;
            $paginated_data->push(static::fromRow($row));
        }
        return $paginated_data;
    }
}
