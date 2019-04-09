<?php

namespace BadChoice\Thrust\Actions;

use BadChoice\Thrust\Facades\Thrust;

class SaveOrder extends MainAction
{
    public function display($resourceName, $parent_id = null)
    {
        $title = __('thrust::messages.'.$this->title);
        return view('thrust::actions.saveOrder', [
            'title'        => $title,
            'resourceName' => $resourceName,
            'startAt'      => request('page') ? (request('page') - 1) * Thrust::make($resourceName)->pagination : 0
        ])->render();
    }
}
