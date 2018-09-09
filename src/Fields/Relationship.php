<?php

namespace BadChoice\Thrust\Fields;


abstract class Relationship extends Field
{
    protected $relationDisplayField = 'name';

    public function getRelation($object){
        return $object->{$this->field}();
    }

    public function relationDisplayField($relationDisplayField = 'name')
    {
        $this->relationDisplayField = $relationDisplayField;
        return $this;

    }

}