<?php

namespace BadChoice\Thrust\Fields;

use BadChoice\Thrust\ResourceManager;

class BelongsToMany extends Relationship
{
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
        return $this->relatedQuery($object, $this->allowDuplicates)->get();
    }

    public function displayInEdit($object, $inline = false)
    {
        $this->withLink = false;
        return view('thrust::fields.info',[
            "title" => $this->getTitle(),
            "value" => $this->displayInIndex($object),
            "inline" => $inline,
        ]);
    }


}