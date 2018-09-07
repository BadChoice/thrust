<?php

namespace BadChoice\Thrust\Fields;


class BelongsTo extends Relationship
{
    protected $allowNull = false;

    public function allowNull($allowNull = true)
    {
        $this->allowNull = $allowNull;
        return $this;
    }
    public function displayInIndex($object)
    {
        $relation = $object->{$this->field};
        return $relation->{$this->relationDisplayField} ?? '--';
    }

    public function getOptions($object)
    {
        $possibleRelations = $this->getRelation($object)->getRelated()->pluck($this->relationDisplayField, 'id');
        if ($this->allowNull) return array_merge(["" => "--"], $possibleRelations->toArray());
        return $possibleRelations;
    }

    public function displayInEdit($object)
    {
        return view('thrust::fields.select',[
            'title' => $this->getTitle(),
            'field' => $this->getRelation($object)->getForeignKey(),
            'value' => $object->{$this->field}->id ?? null,
            'options' => $this->getOptions($object),
        ]);
    }

}