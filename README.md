## Prerequisites
- php8.2
- laravel/framework:11.0
- Vue3
- Node.js

Please note that there is a Dockerfile and docker-compose.yml file that will set up all the necessary enviroment

It is recommended that you use Docker for best compatibility

## Production
- The follwing project is hosted in https://globally.grgbishal.com
- The swagger API documentation is hosted here: https://leonschiffer.github.io/GlobalyInterviewTestSwagger (You can call the API directly from there)
- This project has **_deploy.yml_** in **_.github/workflows_** folder which will automatically deploy new changes from main branch to production server

## External Packages Used
- **phpoffice/phpspreadsheet**: A php package to handle reading and writing to excel files. I chose this package over others because it provided the most flexibility
- **laravel/breeze**: To set up vue with inertia

## Setting up
- cp .env.example .env
- composer install
- php artisan key:generate
- npm run build
  
## Env
- Please add the following variables to .env first
```bash
# Either csv or xlsx
EXCEL_DRIVER=csv
```
## Using Docker
- Just run 'docker compose up' and it will set everything up
- You still have to set the env keys mentioned above though

## Understanding the architecture

- All the csv files are generated in the storage/app folder

#### BaseExcelModel.php
```php
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
```
- The project has a BaseExcelModel.php
- Since the requirement was to store the data in a csv file instead of a SQL database, this base abstract class was formed
- This class is the equivalent of Illuminate\Database\Eloquent\Model class that every eloquoent model by default extends
- What each and every function does is explained using the PHPDoc

#### Client.php
```php
<?php

namespace App\Models;

use Illuminate\Support\Str;

class Client extends BaseExcelModel
{
    /**
     * The eligible genders. Use in StoreClientRequest
     * @see \App\Http\Requests\Api\Client\StoreClientRequest
     */
    const GENDERS = [
        "Male",
        "Female",
        "Others",
    ];

    /**
     * The eligible preferred contact modes. Use in StoreClientRequest
     * @see \App\Http\Requests\Api\Client\StoreClientRequest
     */
    const PREFERRED_CONTACT_MODE = [
        "Phone",
        "Email"
    ];

    /**
     * This abstract method needs to be implemented by all classes that extends BaseExcelModel
     * This is the name of the excel file that the data will be saved to
     * @see BaseExcelModel
     */
    public static function getFileName(): string
    {
        return "client";
    }

    public function __construct(
        public string $ulid,
        public string $name,
        public string $gender,
        public string $phone,
        public string $email,
        public string $address,
        public string $nationality,
        public string $dob,
        public string $education_background,
        public ?string $preferred_contact_mode,
        public string $created_at
    )
    {
    }

    /**
     * Create a new Client object from an array
     * Used in ClientController
     * @see \App\Http\Controllers\Api\V1\Client\ClientController
     */
    public static function fromArray(array $data)
    {
        return new self(
            Str::ulid(),
            $data["name"],
            $data["gender"],
            $data["phone"],
            $data["email"],
            $data["address"],
            $data["nationality"],
            $data["dob"],
            $data["education_background"],
            $data["preferred_contact_mode"] ?? "",
            now()->format("Y-m-d H:i:s")
        );
    }

    /**
     * Maps the elements in an array to its respective column in the CSV
     * @return array The array with excel's column name as key and the value to be inserted as value
     */
    public function toCsv(): array
    {
        return [
            "A" => $this->ulid,
            "B" => $this->name,
            "C" => $this->gender,
            "D" => $this->phone,
            "E" => $this->email,
            "F" => $this->address,
            "G" => $this->nationality,
            "H" => $this->dob,
            "I" => $this->education_background,
            "J" => $this->preferred_contact_mode,
            "K" => $this->created_at,
        ];
    }

    /**
     * Returns a new object of the following class from the excel row
     * @return Client
     */
    public static function fromRow(array $row): self
    {
        return new self(...$row);
    }
}

```
- This is the equivalent to model class we generate using the **_aritsan make:model ModelName_** command
- This extends the BaseExcelModel mentioned above
- We use this class to read and write to the client excel file

## Stubs
Using the command:
```bash
php artisan stub:publish
```
- All the stubs have been published
- And out of these stubs, request.stub has been modified to make development simpler

## Repository Pattern
- The project uses Repository Design Pattern and all the repository and interfaces have been bound in AppServiceProvider.php
- Repository pattern was chosen for this project, so that if we need to change the excel driver (i.e. csv, excel) in the future, we would not have to change the business logic
- Which means that in the future, instead of csv file, if we were to go with xls, xml, xlsb, xlr type of files, we could do it so by just changing the **_EXCEL_DRIVER_** value in the .env file
- We have SpreadsheetInterface.php which holds the necessary blueprint to create different type of Excel repository (CsvRepository.php and XlsxRepository.php) in this case
- I have set up the code for xlsx driver in this project in the XlsxRepository.php, so try changing the **_EXCEL_DRIVER_** to xlsx in the .env file and see how it works (default value is **_csv_**)
- In the future, if we needed to add more drivers, we could create a repository and have it implement the SpreadsheetInterface.php and write the necessary codes there
- Please read the PHPDoc in the SpreadsheetInterface.php to understand what each function does

#### CsvRepository.php
```php
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
```

#### XlsxRepository.php
```php
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
```
## Helper classes
#### WorksheetService.php
```php
<?php

namespace App\Services;

use App\Models\BaseExcelModel;
use App\Exceptions\Excel\InvalidExcelDriverException;
use App\Repositories\CsvRepository;
use App\Repositories\XlsxRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\BaseReader;

/**
 *
 */
class WorksheetService
{
    public function __construct()
    {

    }

    /**
     * Add a new row to the excel file
     * @param BaseReader $reader The base reader file which is implemented by Xlsx and Csv reader
     * @param array $data The row to be added to the excel file
     * @param string $file_path The full path of the file where the data is to be inserted
     */
    public function insertRow(BaseReader $reader, array $data, string $file_path): Spreadsheet
    {

        $spreadsheet = $reader->load($file_path);
        $sheet = $spreadsheet->getActiveSheet();
        $highest_row = $sheet->getHighestRow();
        $current_row = ($highest_row == 1) ? $highest_row : ++$highest_row;
        $sheet->insertNewRowBefore($current_row);
        foreach ($data as $coordinate => $value) {
            $sheet->setCellValue($coordinate . $current_row, $value);
        }
        return $spreadsheet;
    }

    /**
     * Migrates all the instance of BaseExcelModels it finds
     * @see \App\Providers\AppServiceProvider
     * @param array $excel_dto_classes
     */
    public static function migrate(array $excel_dto_classes)
    {
        foreach ($excel_dto_classes as $excel_dto_class)
        {
            /** @var BaseExcelModel $excel_dto_class */
            $excel_dto_class::migrateFile();
        }
    }

    /**
     * Get respective class according to driver in .env file
     * @return string|InvalidExcelDriverException
     */
    public static function getRepositoryNameByDriver()
    {
        return match (config("app.excel_driver")) {
            "csv" => CsvRepository::class,
            "xlsx" => XlsxRepository::class,
            default => throw new InvalidExcelDriverException
        };
    }
}
```
- The app/Services folder holds the helper class
- In the future, if we were to add more helper classes, we would add it here
- WorksheetService.php class aids the repository classes mentioned above to read and write to excel files
- Please read the PHPDoc to understand what each function does

#### response.php
```php
<?php

/**
 * For send a error json response with a specific format
 */
function responseError($message = "", $status = 500)
{
    return response()->json([
        "message" => $message
    ], $status);
}

/**
 * For send a success json response with a specific format
 */
function responseSuccess($data = [], $message = "", $status = 200)
{
    return response()->json([
        "data" => $data,
        "message" => $message
    ], $status);
}
```
- This is a global helper file, thats why its not inside the App\Services folder
- This has two functions
- responseSuccess: to have a specific format of response upon successful request
- responseError: to have a specific format of response upon unsuccessful request


#### AppServiceProvider.php
```php
<?php

namespace App\Providers;

use App\Models\Client;
use App\Repositories\CsvRepository;
use App\Repositories\ClientRepository;
use Illuminate\Support\ServiceProvider;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Repositories\Interfaces\SpreadsheetInterface;
use App\Repositories\Interfaces\ClientRepositoryInterface;
use App\Repositories\XlsxRepository;
use App\Services\WorksheetService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(ClientRepositoryInterface::class, ClientRepository::class);
        $this->app->bind(SpreadsheetInterface::class, WorksheetService::getRepositoryNameByDriver());

        WorksheetService::migrate([
            Client::class
        ]);
    }
}
```
- In AppServiceProvider, we bind the interfaces to its respective Repository
- You can see that SpreadsheetInterface is being bound dynamically here using the **_EXCEL_DRIVER_** from the .env file
- Another important step here is the WorksheetService::migrate(), this function will create a csv/xlsx file in the storage/app folder where all your data's are stored
- In its argument, it accepts array of classes that extends the BaseExcelModel class
- In the future, if we were to create more Models, we would first create the class, have it extend BaseExcelModel and then add it here and it would automatically create that file in the storage/app folder

## Exceptions
#### InvalidExcelDriverException.php
```php
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
```
- This custom exception is thrown if you set an invalid driver in .env file's **_EXCEL_DRIVER_** variable

## Routing
#### web.php
```php
<?php

use App\Http\Controllers\Client\ClientController;
use Illuminate\Support\Facades\Route;


Route::redirect("/", "/client/create");
Route::resource("client", ClientController::class)->only(["index", "create"]);
```
- There are two routes registered in web.php file using the resource method
- These routes are
- index: to render the list view of client
- create: to render the create form view of client
- It has one more redirect route which basically redirects home page to create route

#### api-v1.php
```php
Route::apiResource("clients", ClientController::class)->only(["index", "store"]);
```
- There are two routes registered in api.php file using the apiResource method
- These routes are
- index: to get list of client
- store: to store a new client
- View the API Swagger documentation here: https://leonschiffer.github.io/GloballyInterviewTestSwagger/

#### deploy.yml
```yml
name: Deploy to Production

on:
    push:
        branches: ["main"]

jobs:
    prod-deploy:
        name: Deployment Process
        runs-on: ubuntu-latest

        steps:
            # - name: Get latest code
            #   uses: actions/checkout@v3
            - name: Deployment via SSH
              uses: appleboy/ssh-action@v1.0.3
              with:
                host: ${{ secrets.HOST }}
                username: ${{ secrets.USERNAME }}
                key: ${{ secrets.PRIVATE_KEY }}
                port: ${{ secrets.PORT }}
                script: |
                    cd /home/grgbish1/globally.grgbishal.com
                    git pull origin main
                    composer install
                    npm run build
                    php artisan migrate --force
```
- The following deploy.yml file in .github/workflows folder will automatically push new changes in the main branch to production server




