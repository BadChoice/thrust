<?php

namespace BadChoice\Thrust\Metrics;
use Illuminate\Support\Facades\DB;

abstract class TrendMetric extends Metric
{
    protected $dateField = 'created_at';

    public function metricTypeName()
    {
        return 'trend';
    }

    public function result()
    {
        return $this->getEmptyDays()->merge($this->result->pluck('count', 'date'));
    }

    public function countByDays($class)
    {
        $this->result = $this->applyRange($class)
            ->groupBy(DB::raw("Date({$this->dateField})"))
            ->orderBy(DB::raw("Date({$this->dateField})"))
            ->select(DB::raw("count('id') as count"), DB::raw("Date({$this->dateField}) as date"))
            ->get();
        return $this;
    }

    public function countByMonths($class)
    {
    }

    public function countByWeeks($class)
    {
    }

    public function countByHours($class)
    {
    }

    public function countByMinutes($class)
    {
    }

    public function averageByMonths($class, $field)
    {
    }

    public function averageByWeeks($class, $field)
    {
    }

    public function averageByDays($class, $field)
    {
    }

    public function averageByHours($class, $field)
    {
    }

    public function averageByMinutes($class, $field)
    {
    }

    private function getEmptyDays()
    {
        $emptyDays = [];
        foreach ($this->obtainPeriod() as $date) {
            $emptyDays[$date->format('Y-m-d')] = 0;
        }
        return collect($emptyDays);
    }

    //Sum
    //Max
    //Min
}
