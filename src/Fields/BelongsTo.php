<?php

namespace BadChoice\Thrust\Fields;


use BadChoice\Thrust\ResourceManager;

class BelongsTo extends Relationship
{
    protected $allowNull = false;
    protected $searchable = false;
    protected $ajaxSearch = false;

    public function allowNull($allowNull = true)
    {
        $this->allowNull = $allowNull;
        return $this;
    }

    public function searchable($searchable = true, $usingAjax = false)
    {
        $this->searchable = $searchable;
        $this->ajaxSearch = $usingAjax;
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
        if ($this->ajaxSearch){
            return view('thrust::fields.selectAjax',[
                'resourceName' => app(ResourceManager::class)->resourceNameFromModel(get_class($object)),
                'title' => $this->getTitle(),
                'field' => $this->getRelation($object)->getForeignKey(),
                'relationship' => $this->field,
                'value' => $object->{$this->field}->id ?? null,
                'id' => $object->id,
            ]);
        }
        return view('thrust::fields.select',[
            'title' => $this->getTitle(),
            'field' => $this->getRelation($object)->getForeignKey(),
            'searchable' => $this->searchable,
            'value' => $object->{$this->field}->id ?? null,
            'options' => $this->getOptions($object),
        ]);
    }

}