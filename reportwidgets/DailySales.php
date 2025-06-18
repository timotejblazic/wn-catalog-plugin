<?php namespace Tb\Catalog\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Carbon\Carbon;
use Tb\Catalog\Models\Order;

class DailySales extends ReportWidgetBase
{
    public function render()
    {
        $this->vars['results'] = $this->getGraphData();

        return $this->makePartial('widget');
    }

    protected function getGraphData()
    {
        $days = 7;
        $orderValueByTime = collect();
//        $orderCountByTime = collect();

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);

            $orderSum = round(Order::whereDate('created_at', $date)->sum('total_amount'), 2);
//            $orderCount = Order::whereDate('created_at', $date)->count();

            $orderValueByTime->push((object)['date' => $date->timestamp, 'orderSum' => $orderSum]);
//            $orderCountByTime->push((object)['date' => $date->timestamp, 'orderCount' => $orderCount]);
        }

        return $orderValueByTime->map(function ($result) {
            return '[' . $result->date . '000' . ',' . $result->orderSum . ']';
        });

//        $orderCountByTime = $orderCountByTime->map(function ($result) {
//            return '[' . $result->date . '000' . ',' . $result->orderCount . ']';
//        });
    }
}
