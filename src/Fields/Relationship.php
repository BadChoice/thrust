<?php

namespace BadChoice\Thrust\Fields;


abstract class Relationship extends Field
{
    public $relationDisplayField = 'name';

    public function getRelation($object){
        return $object->{$this->field}();
    }

    public function getRelationName($object)
    {
        $relation = $object->{$this->field};
        return $relation->{$this->relationDisplayField} ?? '--';
    }

    public function relationDisplayField($relationDisplayField = 'name')
    {
        $this->relationDisplayField = $relationDisplayField;
        return $this;
    }

}