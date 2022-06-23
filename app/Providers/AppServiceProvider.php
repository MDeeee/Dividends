<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Dividends\DividendContract;
use App\Dividends\Dividend;
use App\Portfolios\Portfolio;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DividendContract::class, function($app){ 

            $htmlTable = 'table#companiesDividendsTable tbody tr';
            $dividends_file_path = public_path("files/dividends.csv");
            $portfolios_file_path = public_path("files/portfolios.csv");
            
            $portfolio = new Portfolio($portfolios_file_path);

            return new Dividend($portfolio, $htmlTable, $dividends_file_path);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
