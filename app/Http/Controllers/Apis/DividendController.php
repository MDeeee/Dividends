<?php

namespace App\Http\Controllers\Apis;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Dividends\DividendContract;

class DividendController extends Controller
{
    protected $dividend;

    public function __construct(DividendContract $dividend)
    {
        $this->dividend = $dividend;
    }

    public function list() :array
    {
        return $this->dividend->getDividends();
    }

    public function scrape(Request $request ) :array
    {
        $this->validate($request, ['url' => 'required|url']);

        return $this->dividend->scrapeUrl($request->url);
    }

    public function download() :object
    {
        return $this->dividend->downloadCSVfile();
    }
    
    public function delete() :bool
    {
        return $this->dividend->deleteCSVfile();
    }
}
