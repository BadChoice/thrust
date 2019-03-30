<?php

namespace BadChoice\Thrust\Metrics;

use Illuminate\Support\Facades\DB;

abstract class PartitionMetric extends Metric
{
    private $labelCallback;
    private $colorsCallback;

    public function metricTypeName()
    {
        return 'partition';
    }

    public function getColors()
    {
        if (! $this->colorsCallback) return [];
        return $this->result()->map(function($count, $key){
           return call_user_func($this->colorsCallback, $key);
        })->values();
    }

    protected function result()
    {
        if ($this->labelCallback){
            return $this->result->pluck('count', 'field')->mapWithKeys(function($count, $key){
                return [call_user_func($this->labelCallback, $key) => $count];
            });
        }
        return $this->result->pluck('count', 'field');
    }

    public function count($class, $field)
    {
        $this->result = $this->applyRange($class)
                      ->groupBy($field)->select(DB::raw("$field as field"), DB::raw("count({$field}) as count"))->get();
        return $this;
    }

    public function label($callback)
    {
        $this->labelCallback = $callback;
        return $this;
    }

    public function colors($callback){
        $this->colorsCallback = $callback;
        return $this;
    }
}