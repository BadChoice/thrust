<?php

namespace BadChoice\Thrust\Metrics;

abstract class ValueMetric extends Metric
{
    public function count($class)
    {
        $this->result = $this->applyRange($class)->count();
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
}
