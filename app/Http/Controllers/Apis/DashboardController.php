<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Dividends\DividendContract;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function list(DividendContract $dividend)
    {
        $dividends = $dividend->getDividends();

        $dashboard1 = [];
        $dashboard2 = [];

        foreach ($dividends as $key =>  $dividend) {
            if ($dividend['distribution_amount'] > 0) {

                $portfolio['id'] = $key + 1;
                $portfolio['company'] = $dividend['company'];
                $portfolio['distribution_amount'] = $dividend['distribution_amount'];
                $dashboard1[] = $portfolio;
                
                $dDate = $dividend['distribution_date'];

                if ($dDate !== "N/A") {
                    $dividendByMonth['name'] = $dividend['company'];
                    $dividendByMonth['month_number'] = Carbon::parse($dDate)->format('m');
                    $dividendByMonth['amount'] = $dividend['distribution_amount'];
                    $dashboard2[] = $dividendByMonth;
                }
            }
        }

        return [
            'dashboard1' => $dashboard1, 
            'dashboard2' => $this->getMonthsArray($dashboard2)
        ];
    }

    private function getMonthsArray($dividends)
    {
        $yAxis = [];
        
        foreach ($dividends as $key => $dividend) {

            $data = [];

            foreach (range(1, 12) as $number) {

                $item = (int) $dividend['month_number'] === $number ? $dividend['amount'] : 0;

                $data[] = $item;
            }

            $yAxis[$key]['name'] = $dividend['name'];
            $yAxis[$key]['data'] = $data;
        }

        return $yAxis;
    }
}
