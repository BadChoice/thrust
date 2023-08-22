<?php

namespace BadChoice\Thrust\Controllers;

use BadChoice\Thrust\Exporter\CSVExporter;
use BadChoice\Thrust\Facades\Thrust;
use BadChoice\Thrust\Importer\Importer;
use BadChoice\Thrust\ResourceGate;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class ThrustImportController extends Controller
{
    public function index($resourceName)
    {
        $resource = Thrust::make($resourceName);
        $this->validateImportable($resource);
        app(ResourceGate::class)->check($resource, 'create');

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
        $this->validateImportable($resource);
        app(ResourceGate::class)->check($resource, 'create');

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
        $this->validateImportable($resource);
        app(ResourceGate::class)->check($resource, 'create');
        $importer = new Importer(request('csv'), $resource);
        try {
            DB::connection()->disableQueryLog();
            $imported = $importer->import(request('mapping'));
        }catch(\Exception $e){
//            dd($e->validator->errors()->getMessages());
            return view('thrust::importer.failed', [
                'resource' => $resource,
                "resourceName" => $resourceName,
                "exception" => $e
            ]);
        }

        return redirect()->route('thrust.index', $resourceName)->with(['message' => "Imported {$imported}" ]);
    }

    protected function validateImportable($resource): void
    {
        if (!$resource::$importable) {
            abort(403, "This resource is not importable");
        }
    }
}
