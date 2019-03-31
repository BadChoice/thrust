<?php

namespace BadChoice\Thrust\Metrics;

use Illuminate\Support\Facades\DB;

abstract class PartitionMetric extends Metric
{
    private $relationKey;

    public function metricTypeName()
    {
        return 'partition';
    }

    public function getColors()
    {
        return $this->result->map(function($value){
            if (!$value->{$this->relationKey}){
                return "#eee";
            }
            return $value->{$this->relationKey}->color ?? $this->colorFromName($value->{$this->relationKey}->name);
        })->values();
    }

    protected function result()
    {
        return $this->result->mapWithKeys(function($value){
            return [$value->{$this->relationKey}->name ?? '--' => $value->count];
        });
    }

    public function count($class, $field)
    {
        $foreign_key = (new $class)->$field()->getForeignKey();
        $this->relationKey = $field;
        $this->result = $this->applyRange($class)->with($field)
                      ->groupBy($foreign_key)
                      ->orderBy('count','DESC')
                      ->select($foreign_key, DB::raw("$foreign_key as field"), DB::raw("count(id) as count"))->get();
        return $this;
    }

    public function colorFromName($text)
    {
        return "#".substr(md5($text), 0, 6);
    }
}