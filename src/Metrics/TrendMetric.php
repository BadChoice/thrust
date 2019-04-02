<?php

namespace BadChoice\Thrust\Metrics;
use Illuminate\Support\Facades\DB;

abstract class TrendMetric extends Metric
{
    protected $dateField = 'created_at';
    private $byDays = false;

    public function metricTypeName()
    {
        return 'trend';
    }

    public function result()
    {
        if ($this->byDays) {
            return $this->getEmptyDays()->merge($this->result->pluck('count', 'date'));
        }
        return $this->result->mapWithKeys(function($row){
            return [wordwrap($row->date, 4, " ", true) => $row->count];
        });
    }

    public function countByDays($class)
    {
        return $this->doQueryByDays($class, 'count', 'id');
    }

    public function countByMonths($class)
    {
        $this->result = $this->applyRange($class)
            ->groupBy(DB::raw("Extract(YEAR_MONTH FROM {$this->dateField})"))
            ->orderBy(DB::raw("Extract(YEAR_MONTH FROM {$this->dateField})"))
            ->select(DB::raw("count('id') as count"), DB::raw("Extract(YEAR_MONTH FROM {$this->dateField}) date"))
            ->get();
        return $this;
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

    public function sumByDays($class, $field)
    {
        return $this->doQueryByDays($class, 'sum', $field);
    }

    public function doQueryByDays($class, $operation, $field)
    {
        $this->result = $this->applyRange($class)
            ->groupBy(DB::raw("Date({$this->dateField})"))
            ->orderBy(DB::raw("Date({$this->dateField})"))
            ->select(DB::raw("{$operation}('{$field}') as count"), DB::raw("Date({$this->dateField}) as date"))
            ->get();
        $this->byDays = true;
        return $this;
    }

    public function averageByDays($class, $field)
    {
        return $this->doQueryByDays($class, 'avg', $field);
    }

    public function averageByMonths($class, $field)
    {
    }

    public function averageByWeeks($class, $field)
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
