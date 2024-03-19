<?php

namespace App\Models;

use App\Repositories\Interfaces\SpreadsheetInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

/**
 * This abstract class is supposed to be extended by all models whose data we will be storing in a excel file
 */
abstract class BaseExcelModel
{
    /**
     * This file name is equivalent to table name
     * A file of this name will be created in storage/app folder
     * Which will store all of our data related to this Dto
     * @return string The name of the file
     */
    abstract public static function getFileName(): string;
    abstract public static function fromRow(array $row);

    public function __construct(SpreadsheetInterface $spreadsheet)
    {
        File::makeDirectory($spreadsheet->getFullFilePath(static::getFileName()));
    }

    /**
     * This function returns the data from the respective excel file
     * The data are paginated
     * It is similar to eloquent's Model::all() function
     * @param int $limit The number of rows you want to query from the excel file
     * @param int $page The page number used for pagination
     * The combination of $limit and $page will be used to calculate the offset
     * @return LengthAwarePaginator This will give the meta data of the pagination
     */
    public static function all(int $limit = null, int $page = null): LengthAwarePaginator
    {

        $spreadsheet_repository = app()->make(SpreadsheetInterface::class);
        $worksheet = $spreadsheet_repository->getWorksheet(static::getFileName());
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

    /**
     * Contains logic for selecting only specific rows for paginating purpose
     * @param array $rows All the data from the excel file i.e. unpaginated data
     * @param int $limit
     * @param int $page
     */
    private static function sliceForPagination(array $rows,  int $limit, int $page)
    {
        return array_slice(
            $rows,
            ($limit * $page) - $limit,
            $limit
        );
    }

    /**
     * Paginates the given data according to $limit and $page
     * @param array $data Data to be paginated
     * @param int $limit
     * @param int @page
     */
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

    /**
     * Contains the logic for creating the excel file where data is stored. This method is invoked in AppServiceProvider
    */
    public static function migrateFile()
    {
        /** @var SpreadsheetInterface @spreadsheet */
        $spreadsheet = app()->make(SpreadsheetInterface::class);
        $full_file_path = $spreadsheet->getFullFilePath(static::getFileName());
        if (!file_exists($full_file_path)) {
            $spreadsheet->createMigrationfile($full_file_path);
        }
    }
}
