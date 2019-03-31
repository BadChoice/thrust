<?php

namespace BadChoice\Thrust\Metrics;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

abstract class Metric
{
    protected $dateField = 'created_at';
    protected $result;
    protected $cacheFor = 0;

    abstract public function calculate();

    abstract public function uriKey();

    abstract protected function result();
    abstract public function metricTypeName();

    public function getResult()
    {
        return Cache::remember($this->getCacheKey(), $this->cacheFor, function(){
            if (! $this->result) { $this->calculate(); }
            return $this->result();
        });
    }

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
     * @param CarbonPeriod $period the range to apply, if null, the default used
     * @return Builder the query with the date ranges applied
     */
    protected function applyRange($class, $period = null){
        if (! $period) $period = $this->obtainPeriod();
        return $this->wrapQueryBuilder($class)->whereBetween($this->dateField, [$period->first(), $period->last()]);
    }

    /**
     * @return CarbonPeriod
     */
    protected function obtainPeriod()
    {
        $range = $this->getRangeKey();
        return CarbonPeriod::create(Carbon::now()->subDays($range), Carbon::now());
    }

    protected function obtainPreviousPeriod(){
        $range = $this->getRangeKey();
        return CarbonPeriod::create(Carbon::now()->subDays($range * 2), Carbon::now()->subDays($range));
    }

    private function wrapQueryBuilder($class)
    {
        return ($class instanceof Builder) ? $class : $class::query();
    }

    /**
     * @return array|\Illuminate\Http\Request|string
     */
    protected function getRangeKey()
    {
        return request('metricRange') ?? array_keys($this->ranges())[0];
    }

    /**
     * @return string
     */
    protected function getCacheKey()
    {
        return $this->uriKey() . '-' . $this->getRangeKey();
    }

    public function getTitle(){
        return ucwords(strtolower(str_replace('-', ' ', $this->uriKey())));
    }

    public function getSize(){
        return 'col-min33';
    }
}
