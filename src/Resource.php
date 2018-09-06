<?php

namespace BadChoice\Thrust;

abstract class Resource{

    protected $class;

    abstract public function getFields();

    public function getRows(){
        return ($this->class)::paginate(25);
    }


}