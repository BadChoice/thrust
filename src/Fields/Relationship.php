<?php

namespace BadChoice\Thrust\Fields;


abstract class Relationship extends Field
{
    protected $relationDisplayField = 'name';

    public function getRelation($object){
        return $object->{$this->field}();
    }

}