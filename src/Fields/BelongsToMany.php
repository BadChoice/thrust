<?php

namespace BadChoice\Thrust\Fields;


use BadChoice\Thrust\Fields\Traits\Searchable;
use BadChoice\Thrust\ResourceManager;

class BelongsToMany extends Relationship
{
    use Searchable;

    public $withLink        = false;
    public $allowDuplicates = false;

    public $indexTextCallback = null;
    public $pivotFields = [];

    public function displayInIndex($object)
    {
        return view('thrust::fields.belongsToMany',[
            "value"         => $this->getIndexText($object),
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

    public function pivotFields($pivotFields){
        $this->pivotFields = $pivotFields;
        return $this;
    }

    public function allowDuplicates($allowDuplicates = true)
    {
        $this->allowDuplicates = $allowDuplicates;
        return $this;
    }

    public function displayInIndexCallback($callback){
        $this->indexTextCallback = $callback;
        return $this;
    }

    public function getTitle()
    {
        return $this->title ?? trans_choice(config('thrust.translationsPrefix') . str_singular($this->field), 2);
    }

    public function getIndexText($object)
    {
        if ($this->indexTextCallback){
            return call_user_func($this->indexTextCallback, $object);
        }
        return $object->{$this->field}->pluck($this->relationDisplayField)->implode(', ');
    }

    public function getOptions($object)
    {
        $relatedQuery = $this->getRelation($object)->getRelated();
        if ($this->allowDuplicates) return $relatedQuery->all();
        return $relatedQuery->query()->whereNotIn('id', $object->{$this->field}->pluck('id'))->get();
    }

    public function displayInEdit($object, $inline = false)
    {
        $this->withLink = false;
        return view('thrust::fields.info',[
            "title" => $this->getTitle(),
            "value" => $this->displayInIndex($object),
        ]);
    }


}