<?php

namespace BadChoice\Thrust\Metrics;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Query\Builder;

abstract class Metric
{
    protected $dateField = 'created_at';
    protected $result;

    abstract public function calculate();

    abstract public function uriKey();

    /**
     * @return \Carbon\Carbon date until the cache should be valid
     */
    public function cacheFor()
    {
        return null;
    }

    public function ranges()
    {
        return [
            30    => '30 Days',
            60    => '60 Days',
            365   => '365 Days',
            'MTD' => 'Month To Date',
            'QTD' => 'Quarter To Date',
            'YTD' => 'Year To Date',
        ];
    }

    /**
     * @param $class
     * @return Builder the query with the date ranges applied
     */
    protected function applyRange($class){
        $period = $this->obtainPeriod();
        return $this->wrapQueryBuilder($class)->whereBetween($this->dateField, [$period->first(), $period->last()]);
    }

    /**
     * @return CarbonPeriod
     */
    protected function obtainPeriod()
    {
        $range = request('metricRange') ?? array_keys($this->ranges())[0];
        return CarbonPeriod::create(Carbon::now()->subDays($range), Carbon::now());
    }

    private function wrapQueryBuilder($class)
    {
        return ($class instanceof Builder) ? $class : $class::query();
    }
}
