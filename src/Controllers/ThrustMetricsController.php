<?php

namespace BadChoice\Thrust\Controllers;

class ThrustMetricsController
{
    public function show($metric)
    {
        $metricClass = base64_decode($metric);
        $metric = new $metricClass;
        return view('thrust::metrics.trendMetric', ['metric' => $metric]);
    }
}