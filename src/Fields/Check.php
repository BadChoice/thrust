<?php

namespace BadChoice\Thrust\Fields;


class Check extends Text
{
    public function displayInIndex($object)
    {
        return $object->{$this->field} ? '<i class="fa fa-check green"></i>' : '<i class="fa fa-times red" style="color:red"></i>';
    }

    public function displayInEdit($object)
    {
        return view('thrust::fields.check',[
            'title' => $this->getTitle(),
            'field' => $this->field,
            'value' => $this->getValue($object),
        ]);
    }

}