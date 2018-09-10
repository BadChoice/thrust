<?php

namespace BadChoice\Thrust\Fields;


class BelongsToMany extends Relationship
{

    public function displayInIndex($object)
    {
        $many = $object->{$this->field};
        return $many->pluck($this->relationDisplayField)->implode(', ');
    }

    public function getTitle()
    {
        return $this->title ?? trans_choice(config('thrust.translationsPrefix') . str_singular($this->field), 2);
    }

    public function displayInEdit($object)
    {
        return view('thrust::fields.info',[
            "title" => $this->getTitle(),
            "value" => $this->displayInIndex($object),
        ]);
    }

}