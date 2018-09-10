<?php

namespace BadChoice\Thrust\Fields;


use BadChoice\Thrust\ResourceManager;

class BelongsToMany extends Relationship
{
    public $withLink = false;
    public $allowDuplicates = false;

    public function displayInIndex($object)
    {
        return view('thrust::fields.belongsToMany',[
            "value"         => $object->{$this->field}->pluck($this->relationDisplayField)->implode(', '),
            "withLink"      => $this->withLink,
            "relationship"  => $this->field,
            "id"            => $object->id,
            "resourceName"  => app(ResourceManager::class)->resourceNameFromModel($object),
        ]);
    }

    public function withLink($withLink = true)
    {
        $this->withLink = $withLink;
        return $this;
    }

    public function allowDuplicates($allowDuplicates = true)
    {
        $this->allowDuplicates = $allowDuplicates;
        return $this;
    }

    public function getTitle()
    {
        return $this->title ?? trans_choice(config('thrust.translationsPrefix') . str_singular($this->field), 2);
    }

    public function getOptions($object)
    {
        $relatedQuery = $this->getRelation($object)->getRelated();
        if ($this->allowDuplicates) return $relatedQuery->all();
        return $relatedQuery->query()->whereNotIn('id', $object->{$this->field}->pluck('id'))->get();
    }

    public function displayInEdit($object)
    {
        $this->withLink = false;
        return view('thrust::fields.info',[
            "title" => $this->getTitle(),
            "value" => $this->displayInIndex($object),
        ]);
    }

}