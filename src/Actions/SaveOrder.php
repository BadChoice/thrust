<?php

namespace BadChoice\Thrust\Actions;

use BadChoice\Thrust\Facades\Thrust;

class SaveOrder extends MainAction
{
    public function display($resourceName, $parent_id = null)
    {
        $title    = __('thrust::messages.'.$this->title);
        $resource = Thrust::make($resourceName);
        $tooltip  = __($resource::$sortTooltip);
        return view('thrust::actions.saveOrder', [
            'title'        => $title,
            'resourceName' => $resourceName,
            'startAt'      => request('page') ? (request('page') - 1) * $resource->pagination : 0,
            'tooltip'      => $tooltip,
        ])->render();
    }
}
