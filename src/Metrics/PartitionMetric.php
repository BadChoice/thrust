<?php

namespace BadChoice\Thrust\Metrics;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

abstract class PartitionMetric extends Metric
{
    private $field;
    private $foreign_key;
    private $namesCallback;
    private $colorsCallback;

    public function metricTypeName()
    {
        return 'partition';
    }

    public function getColors()
    {
        return $this->result->map(function($value){
            if ($this->colorsCallback){
                return call_user_func($this->colorsCallback, $value);
            }
            if (! $value->{$this->field}){
                return "#eee";
            }
            if ($this->foreign_key) {
                return $value->{$this->field}->color ?? $this->colorFromName($value->{$this->field}->name);
            }
            return $this->colorFromName($value->{$this->field});
        })->values();
    }

    protected function result()
    {
        return $this->result->mapWithKeys(function($value){
            if ($this->namesCallback){
                return [call_user_func($this->namesCallback, $value) . " ({$value->count})" => $this->applyFormat($value->count)];
            }
            if ($this->foreign_key) {
                return [($value->{$this->field}->name ?? '--') . " ({$value->count})" => $this->applyFormat($value->count)];
            }
            return [($value->{$this->field} ?? '--') . " ({$value->count})" => $this->applyFormat($value->count)];
        });
    }

    public function count($class, $field)
    {
        $queryField = $this->setField($class, $field);
        $this->result = $this->withRelationShip($this->applyRange($class))
            ->groupBy($queryField)
            ->orderBy('count','DESC')
            ->select($queryField, DB::raw("$queryField as field"), DB::raw("count(id) as count"))->get();
        return $this;
    }

    public function colorFromName($text)
    {
        return "#".substr(md5($text), 0, 6);
    }

    private function setField($class, $field)
    {
        $foreign_key = null;
        $this->field = $field;
        try {
            if ($class instanceof Builder){
                $this->foreign_key = $this->relationGetForeignKey($class->getModel()->$field());
            }else {
                $this->foreign_key = $this->relationGetForeignKey((new $class)->$field());
            }
        }catch(\Exception $e){

        }
        return $this->getQueryField();
    }

    protected function relationGetForeignKey($relation){
        if (method_exists($relation, 'getForeignKey')) {
            return $relation->getForeignKey();
        }
        return $relation->getForeignKeyName();
    }
}

    protected function getQueryField(){
        if ($this->foreign_key) return $this->foreign_key;
        return $this->field;
    }

    private function withRelationShip(\Illuminate\Database\Eloquent\Builder $query)
    {
        if (! $this->foreign_key) return $query;
        return $query->with($this->field);
    }

    public function names($callback)
    {
        $this->namesCallback = $callback;
    }

    public function colors($callback)
    {
        $this->colorsCallback = $callback;
    }
}