<?php

namespace BadChoice\Thrust\Actions;

class SaveOrder extends MainAction
{

    public function display($resourceName)
    {
        $title = __('thrust::messages.'.$this->title);
        return view('thrust::actions.saveOrder', [
            'title' => $title,
            'resource' => ucfirst(str_singular($resourceName))
        ])->render();
    }

}