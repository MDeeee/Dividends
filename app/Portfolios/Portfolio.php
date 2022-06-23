<?php
 
namespace App\Portfolios;

use Illuminate\Support\Facades\File;
use App\Portfolios\PortfolioContract;

class Portfolio implements PortfolioContract
{
    private $file_path;

    public function __construct(string $file_path) 
    {
        $this->file_path = $file_path;
    }

    public function getPortfolios() : array
    {
        return $this->parsePortfolioFile();
    }
 
    private function parsePortfolioFile()
    {
        $portfolios = [];

        if (File::exists($this->file_path)) {
            
            if (($open = fopen($this->file_path, "r")) !== FALSE) {
    
                while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                    $portfolios[] = $data;
                }
    
                fclose($open);
            }
        }

        if (count($portfolios) > 1) {
            // Flip array keys from csv schema to json schema
            $newPortfoliosArr = [];
            foreach ($portfolios as $key => $portfolio) {
                if ($key > 0) {
                    // Rows after header row
                    // this is the main issue where only the first row hold all array keys
                    $item['id'] = $portfolio[0];
                    $item['company'] = $portfolio[1];
                    $item['shares'] = $portfolio[2];
                    $newPortfoliosArr[] = $item;
                }
            }

            return $newPortfoliosArr;
        }

        return $portfolios;
    }
}
