<?php

namespace BadChoice\Thrust\Controllers;

use BadChoice\Thrust\Exporter\CSVExporter;
use BadChoice\Thrust\Facades\Thrust;
use BadChoice\Thrust\Importer\Importer;
use BadChoice\Thrust\ResourceGate;
use Illuminate\Routing\Controller;

class ThrustImportController extends Controller
{
    public function index($resourceName)
    {
        $resource = Thrust::make($resourceName);
        app(ResourceGate::class)->check($resource, 'index');

        return view('thrust::importer.index', [
            'resourceName' => $resourceName,
            'resource'     => $resource,
        ]);
    }

    public function uploadCsv($resourceName)
    {
        request()->validate([
            'csv' => 'required|mimetypes:text/csv,text/plain,application/csv,text/comma-separated-values,text/anytext,application/octet-stream,application/txt'
        ]);
        $resource = Thrust::make($resourceName);
        app(ResourceGate::class)->check($resource, 'index');

        $importer = new Importer(request()->file('csv')->getContent(), $resource);

        return view('thrust::importer.show', [
            'resourceName' => $resourceName,
            'resource'     => $resource,
            'importer'     => $importer
        ]);
    }

    public function store($resourceName)
    {
        $resource = Thrust::make($resourceName);
        app(ResourceGate::class)->check($resource, 'index');

        $importer = new Importer(request('csv'), $resource);
        try {
            $imported = $importer->import(request('mapping'));
        }catch(\Exception $e){
            return redirect()->back()->with(["message" => "Import failed: {$e->getMessage()}"]);
        }

        return redirect()->route('thrust.index', $resourceName)->with(['message' => "Imported {$imported}" ]);
    }
}
