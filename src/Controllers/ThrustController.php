<?php

namespace BadChoice\Thrust\Controllers;

use BadChoice\Thrust\Html\Edit;
use BadChoice\Thrust\ResourceManager;
use Illuminate\Routing\Controller;

class ThrustController extends Controller
{

    public function edit($resourceName, $id)
    {
        $resource = app(ResourceManager::class)->make($resourceName);
        return (new Edit($resource))->show($id);
    }

    public function update($resourceName, $id)
    {
        app(ResourceManager::class)->make($resourceName)
                                   ->update($id, request()->all());
        return back()->withMessage(__('updated'));
    }

    public function delete($resourceName, $id)
    {
        app(ResourceManager::class)->make($resourceName)
                                   ->delete($id);
        return back()->withMessage(__('deleted'));
    }
}