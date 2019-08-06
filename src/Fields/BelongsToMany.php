<?php

namespace BadChoice\Thrust\Fields;

use BadChoice\Thrust\ResourceFilters\Search;
use BadChoice\Thrust\ResourceManager;

class BelongsToMany extends Relationship
{
    public $allowDuplicates   = false;
    public $excludeNonDuplicatesInSearch = true;
    public $indexTextCallback = null;
    public $pivotFields       = [];
    public $objectFields      = [];
    public $icon              = null;
    public $hideName;

    public $sortable     = false;
    public $sortField    = 'order';

    public function displayInIndex($object)
    {
        return view('thrust::fields.belongsToMany', [
            'value'         => $this->getIndexText($object),
            'withLink'      => $this->withLink,
            'relationship'  => $this->field,
            'id'            => $object->id,
            'icon'          => $this->icon,
            'resourceName'  => app(ResourceManager::class)->resourceNameFromModel($object),
        ]);
    }

    public function sortable($sortable = true, $sortField = 'order')
    {
        $this->sortable  = $sortable;
        $this->sortField = 'order';
        return $this;
    }

    public function icon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    public function objectFields($objectFields)
    {
        $this->objectFields = $objectFields;
        return $this;
    }

    public function pivotFields($pivotFields)
    {
        $this->pivotFields = $pivotFields;
        return $this;
    }

    public function allowDuplicates($allowDuplicates = true, $excludeNonDuplicatesInSearch = true)
    {
        $this->allowDuplicates = $allowDuplicates;
        $this->excludeNonDuplicatesInSearch = $excludeNonDuplicatesInSearch;
        return $this;
    }

    public function hideName($hideName = true)
    {
        $this->hideName = $hideName;
        return $this;
    }

    public function displayInIndexCallback($callback)
    {
        $this->indexTextCallback = $callback;
        return $this;
    }

    public function getTitle($forHeader = false)
    {
        if ($forHeader && $this->withoutIndexHeader) return "";
        return $this->title ?? trans_choice(config('thrust.translationsPrefix') . str_singular($this->field), 2);
    }

    public function getIndexText($object)
    {
        if ($this->indexTextCallback) {
            return call_user_func($this->indexTextCallback, $object);
        }
        if ($this->icon) {
            return "";
        }
        return $object->{$this->field}->pluck($this->relationDisplayField)->implode(', ');
    }

    public function getOptions($object)
    {
        if (! $this->allowDuplicates && ! $this->excludeNonDuplicatesInSearch){
            return $this->relatedQuery($object, true)->get();
        }
        return $this->relatedQuery($object, $this->allowDuplicates)->get();
    }

    public function relatedQuery($object, $allowDuplicates = true)
    {
        $query = parent::relatedQuery($object, $allowDuplicates);
        if ($this->sortable) {
            return $query->orderBy($this->sortField);
        }
        return $query;
    }

    public function search($object, $search)
    {
        return Search::apply($this->getRelation($object), $search, [$this->relationDisplayField]);
    }

    public function displayInEdit($object, $inline = false)
    {
        $this->withLink = false;
        return view('thrust::fields.info', [
            'title'  => $this->getTitle(),
            'value'  => $this->displayInIndex($object),
            'inline' => $inline,
        ]);
    }
}
