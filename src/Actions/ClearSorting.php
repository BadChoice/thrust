<?php

namespace BadChoice\Thrust\Actions;

class ClearSorting extends MainAction
{
    public function display($resourceName, $parent_id = null)
    {
        $title = __('thrust::messages.'.$this->title);
        return view('thrust::actions.clearSorting', [
            'title'        => $title,
        ])->render();
    }
}
