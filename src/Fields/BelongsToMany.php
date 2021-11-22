<?php

namespace BadChoice\Thrust\Fields;

use Illuminate\Support\Str;
use BadChoice\Thrust\ResourceManager;
use BadChoice\Thrust\ResourceFilters\Search;

class BelongsToMany extends Relationship
{
    public $allowDuplicates              = false;
    public $excludeNonDuplicatesInSearch = true;
    public $indexTextCallback            = null;
    public $pivotFields                  = [];
    public $objectFields                 = [];
    public $withCount                    = false;
    public $icon                         = null;
    public $hideName;

    public $sortable     = false;
    public $sortField    = 'order';

    public $relatedSortable   = false;
    public $relatedSortField  = 'order';

    public $relatedQueryOrderField     = 'id';
    public $relatedQueryOrderDirection = 'asc';

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

    public function displayInEdit($object, $inline = false)
    {
        $this->withLink = false;
        return view('thrust::fields.info', [
            'title'  => $this->getTitle(),
            'value'  => $this->displayInIndex($object),
            'inline' => $inline,
        ]);
    }

    public function sortable($sortable = true, $sortField = 'order')
    {
        $this->sortable  = $sortable;
        $this->sortField = $sortField;
        return $this;
    }

    public function relatedSortable($relatedSortable = true, $relatedSortField = 'order')
    {
        $this->relatedSortable  = $relatedSortable;
        $this->relatedSortField = $relatedSortField;
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
        $this->allowDuplicates              = $allowDuplicates;
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
        if ($forHeader && $this->withoutIndexHeader) {
            return '';
        }
        return $this->title ?? trans_choice(config('thrust.translationsPrefix') . Str::singular($this->field), 2);
    }

    public function getIndexText($object)
    {
        if ($this->indexTextCallback) {
            return call_user_func($this->indexTextCallback, $object);
        }
        if ($this->icon) {
            return '';
        }
        if ($this->withCount) {
            return $this->getRelation($object)->count();
        }

        if ($this->sortable) {
            return $object->{$this->field}->sortBy($this->sortField)->pluck($this->relationDisplayField)->implode(', ');
        }
        return $object->{$this->field}->pluck($this->relationDisplayField)->implode(', ');
    }

    public function getOptions($object)
    {
        if (! $this->allowDuplicates && ! $this->excludeNonDuplicatesInSearch) {
            return $this->relatedQuery($object, true)->get();
        }
        return $this->relatedQuery($object, $this->allowDuplicates)->get();
    }

    public function relatedQuery($object, $allowDuplicates = true)
    {
        $query = parent::relatedQuery($object, $allowDuplicates)->with($this->with)
            ->orderBy($this->relatedQueryOrderField, $this->relatedQueryOrderDirection);
        if ($this->relatedSortable) {
            return $query->orderBy($this->relatedSortField);
        }
        return $query;
    }

    public function search($object, $search)
    {
        return Search::apply($this->getRelation($object), $search, $this->searchFields ?? [$this->relationDisplayField]);
    }

    public function onlyCount()
    {
        $this->withCount = true;
        return $this;
    }

    public function mapRequest($data)
    {
        collect($this->pivotFields)->filter(function ($field) use ($data) {
            return isset($data[$field->field]);
        })->each(function ($field) use (&$data) {
            $data[$field->field] = $field->mapAttributeFromRequest($data[$field->field]);
        });
        return $data;
    }

    public function orderRelatedQueryBy(string $field, string $direction = 'asc') : self
    {
        $this->relatedQueryOrderField     = $field;
        $this->relatedQueryOrderDirection = $direction;
        return $this;
    }
}
