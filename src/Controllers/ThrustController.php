<?php

namespace BadChoice\Thrust\Controllers;

use BadChoice\Thrust\Html\Edit;
use Illuminate\Routing\Controller;

class ThrustController extends Controller
{

    public function edit($resource, $id)
    {
        return (new Edit(new \App\Thrust\Tax))->show($id);
    }

    public function update($resource, $id)
    {
        (new \App\Thrust\Tax)->update($id, request()->all());
        return back()->withMessage(__('updated'));
    }

    public function delete($resource, $id)
    {
        (new \App\Thrust\Tax)->delete($id);
        return back()->withMessage(__('deleted'));
    }
}