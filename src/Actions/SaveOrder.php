<?php

namespace BadChoice\Thrust\Actions;

class SaveOrder extends MainAction
{

    public function display($resourceName,  $parent_id = null)
    {
        $title = __('thrust::messages.'.$this->title);
        return view('thrust::actions.saveOrder', [
            'title' => $title,
            'resourceName' => $resourceName
        ])->render();
    }

}