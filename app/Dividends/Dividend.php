<?php

namespace App\Dividends;

use App\Dividends\DividendContract;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Weidner\Goutte\GoutteFacade as Goutte;

class Dividend implements DividendContract
{
    private $htmlTable;
    private $file_path;
    private $portfolios;

    public function __construct(object $portfolio, string $htmlTable, string $file_path) 
    {
        $this->htmlTable = $htmlTable;
        $this->file_path = $file_path;
        $this->portfolios = $portfolio->getPortfolios();
    }

    public function getDividends() :array
    {
        $dividends = $this->readCSVfile();

        return $this->parseCSVdata($dividends);
    }

    public function scrapeUrl($url) :array
    {
        $dividends = $this->getDividendsFromUrl($url);

        $this->createCSVfile($dividends);

        return $dividends;
    }

    public function downloadCSVfile() :object
    {
        $this->checkIfFileExist();

        $headers  = ['Content-Type' => 'text/csv'];
        
        return Response::download($this->file_path, "dividends.csv", $headers);
    }

    public function deleteCSVfile() :bool
    {
        $this->checkIfFileExist();

        return File::delete($this->file_path);
    }
 
    private function readCSVfile() :array
    {
        $dividends = [];

        if (File::exists($this->file_path)) {
            
            if (($open = fopen($this->file_path, "r")) !== FALSE) {
    
                while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                    $dividends[] = $data;
                }
    
                fclose($open);
            }
        }

        return $dividends;
    }

    private function parseCSVdata($dividends) :array
    {
        // if file has records
        if (count($dividends) > 1) {
            // Flip array keys from csv schema to json schema
            $newDividendsArr = [];
            foreach ($dividends as $key => $dividend) {
                if ($key > 0) {
                    // Rows after header row
                    // this is the main issue where only the first row hold all array keys in csv file
                    $item['id'] = $dividend[0];
                    $item['announced_date'] = $dividend[1];
                    $item['company'] = $dividend[2];
                    $item['distribution_method'] = $dividend[3];
                    $item['distribution_date'] = $dividend[4];
                    $item['amount'] = $dividend[5];
                    $item['distribution_amount'] = $dividend[6];
                    $newDividendsArr[] = $item;
                }
            }

            return $newDividendsArr;
        }

        return [];
    }

    private function getDividendsFromUrl($url) :array
    {
        $crawler = Goutte::request('GET', $url);   

        $dividends = [];

        $crawler->filter($this->htmlTable)->each(function ($tr, $index) use (&$dividends) {

            $nodeArr['id']                  = $index + 1;
            $nodeArr['announced_date']      = $tr->filter('td')->eq(0)->text();
            $nodeArr['company']             = $tr->filter('td')->eq(1)->text();
            $nodeArr['distribution_method'] = $tr->filter('td')->eq(2)->text();
            $nodeArr['distribution_date']   = $tr->filter('td')->eq(3)->text();
            $nodeArr['amount']              = $tr->filter('td')->eq(4)->text();
            $nodeArr['distribution_amount'] = $this->getDistributionAmount($nodeArr['company'], $nodeArr['amount']);

            $dividends[] = $nodeArr;
        });
        
        return $dividends;
    }

    private function createCSVfile($dividends) :void
    {
        // Create if folder not exist
        if (!File::exists(public_path()."/files")) {
            File::makeDirectory(public_path() . "/files");
        }

        // creating the CSV file
        $filename = $this->file_path;
        $handle = fopen($filename, 'w');

        // adding the first row
        fputcsv($handle, [
            "",
            "Announced Date",
            "Company",
            "Dist. Method",
            "Dist. Date",
            "Amount",
            "Dist. Amount",
        ]);

        // adding the remaining rows
        foreach ($dividends as $dividend) {
            fputcsv($handle, [
                $dividend['id'],
                $dividend['announced_date'],
                $dividend['company'],
                $dividend['distribution_method'],
                $dividend['distribution_date'],
                $dividend['amount'],
                $dividend['distribution_amount']
            ]);
        }

        fclose($handle);
    }

    private function checkIfFileExist()
    {
        if (!File::exists($this->file_path)) {
            return Response::json([
                'message' => 'File not found.'
            ], 404);
        }
    }

    private function getDistributionAmount($company, $amount) :float
    {    
        $index = array_search($company, array_column($this->portfolios, 'company'));

        return $index !== false ? (intval($this->portfolios[$index]['shares']) * floatval($amount)) : 0;
    }
}
