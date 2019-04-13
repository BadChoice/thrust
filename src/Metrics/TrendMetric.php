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
            return $this->getEmptyDays()->merge($this->result->mapWithKeys(function($row){
                return [$row->date => $this->applyFormat($row->count)];
            }));
        }
        return $this->result->mapWithKeys(function($row){
            return [wordwrap($row->date, 4, " ", true) => $this->applyFormat($row->count)];
        });
    }

    public function countByDays($class)
    {
        return $this->doQueryByDays($class, 'count', 'id');
    }

    public function countByMonths($class)
    {
        return $this->doQueryByMonth($class, 'count', 'id');
    }

    public function countByWeeks($class)
    {
    }

    public function countByHours($class)
    {
        return $this->doQueryByHours($class, 'count', 'id');
    }

    public function countByMinutes($class)
    {
    }

    public function sumByDays($class, $field)
    {
        return $this->doQueryByDays($class, 'sum', $field);
    }

    public function averageByDays($class, $field)
    {
        return $this->doQueryByDays($class, 'avg', $field);
    }

    public function averageByMonths($class, $field)
    {
        return $this->doQueryByMonth($class, 'avg', $field);
    }

    public function averageByWeeks($class, $field)
    {
    }

    public function sumByHours($class, $field)
    {
        return $this->doQueryByHours($class, 'sum', $field);
    }

    public function averageByHours($class, $field)
    {
        return $this->doQueryByHours($class, 'avg', $field);
    }

    public function averageByMinutes($class, $field)
    {
    }

    public function doQueryByDays($class, $operation, $field)
    {
        $this->result = $this->applyRange($class)
            ->groupBy(DB::raw("Date({$this->dateField})"))
            ->orderBy(DB::raw("Date({$this->dateField})"))
            ->select(DB::raw("{$operation}(`{$field}`) as count"), DB::raw("Date({$this->dateField}) as date"))
            ->get();
        $this->byDays = true;
        return $this;
    }

    public function doQueryByMonth($class, $operation, $field)
    {
        $this->result = $this->applyRange($class)
            ->groupBy(DB::raw("Extract(YEAR_MONTH FROM {$this->dateField})"))
            ->orderBy(DB::raw("Extract(YEAR_MONTH FROM {$this->dateField})"))
            ->select(DB::raw("{$operation}(`{$field}`) as count"), DB::raw("Extract(YEAR_MONTH FROM {$this->dateField}) date"))
            ->get();
        return $this;
    }

    public function doQueryByHours($class, $operation, $field)
    {
        $this->range = 90;
        $this->result = $this->applyRange($class)
            ->groupBy(DB::raw("Hour({$this->dateField})"))
            ->orderBy(DB::raw("Hour({$this->dateField})"))
            ->select(DB::raw("{$operation}(`{$field}`) as count"), DB::raw("Hour({$this->dateField}) as date"))
            ->get();
        $this->byDays = false;
        return $this;
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
