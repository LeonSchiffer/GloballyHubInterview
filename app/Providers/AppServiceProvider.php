<?php

namespace App\Providers;

use App\DTO\ClientDto;
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
        $this->app->bind(SpreadsheetInterface::class, XlsxRepository::class);

        WorksheetService::migrate([
            ClientDto::class
        ]);
    }
}
