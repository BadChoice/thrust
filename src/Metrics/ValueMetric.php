<?php

namespace BadChoice\Thrust\Metrics;

abstract class ValueMetric extends Metric
{
    protected $previousResult;

    public function metricTypeName()
    {
        return 'value';
    }

    protected function result()
    {
        return $this->result;
    }

    public function getPreviousResult()
    {
        return $this->previousResult;
    }

    public function count($class)
    {
        $this->result = $this->applyRange($class)->count();
        $this->previousResult = $this->applyRange($class, $this->obtainPreviousPeriod())->count();
        return $this;
    }

    public function average($class, $field)
    {
        $this->result = $this->applyRange($class)->avg($field);
        return $this;
    }

    public function max($class, $field)
    {
        $this->result = $this->applyRange($class)->max($field);
        return $this;
    }

    public function min($class, $field)
    {
        $this->result = $this->applyRange($class)->min($field);
        return $this;
    }

    public function getIncreasePercentage()
    {
        if ($this->getPreviousResult() == 0) return 0;
        $increase = $this->getResult() - $this->getPreviousResult();
        $increasePercentage = $increase / $this->getPreviousResult() * 100;
        return $increasePercentage;
    }
}
