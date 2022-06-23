<?php

namespace App\Dividends;

interface DividendContract
{
    public function getDividends() :array;
    public function scrapeUrl($url) :array;
    public function downloadCSVfile() :object;
    public function deleteCSVfile() :bool;

}
