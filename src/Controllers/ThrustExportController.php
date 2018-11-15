<?php

namespace BadChoice\Thrust\Controllers;

use BadChoice\Thrust\Exporter\CSVExporter;
use BadChoice\Thrust\Facades\Thrust;
use BadChoice\Thrust\ResourceGate;
use Illuminate\Routing\Controller;

class ThrustExportController extends Controller
{
    public function index($resourceName)
    {
        $resource = Thrust::make($resourceName);
        app(ResourceGate::class)->check($resource, 'index');

        return (new CSVExporter)->export($resource);
    }
}
