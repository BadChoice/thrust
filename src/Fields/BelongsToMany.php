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
    public $relatedSortField  = 'id';
    public $relatedSortOrder  = 'asc';

    public $withEdit = false;

    public $pivotActiveField = null;
    public $displayMultipleLines = false;

    public function displayInIndex($object)
    {
        return view('thrust::fields.belongsToMany', [
            'value'         => $this->getIndexText($object),
            'withLink'      => $this->withLink,
            'relationship'  => $this->field,
            'id'            => $object->id,
            'icon'          => $this->icon,
            'resourceName'  => app(ResourceManager::class)->resourceNameFromModel($object),
        ])->render();
    }

    public function displayInEdit($object, $inline = false)
    {
        $this->withLink = false;
        return view('thrust::fields.info', [
            'title'  => $this->getTitle(),
            'value'  => $this->displayInIndex($object),
            'inline' => $inline,
        ])->render();
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

        $related = $object->{$this->field};
        if ($this->sortable) {
            $related = $related->sortBy($this->sortField);
        }

        $glue = $this->displayMultipleLines
            ? '<br>'
            : ', ';

        return $related->map(function ($child) {
            return "<span {$this->activeAttributes($child)}>".strip_tags($child->{$this->relationDisplayField}).'</span>';
        })->implode($glue);
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
            ->orderBy($this->relatedSortField, $this->relatedSortOrder);
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

    public function displayInMultipleLines(bool $displayMultipleLines = false) : self
    {
        $this->displayMultipleLines = $displayMultipleLines;
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

    public function withPivotActiveField(string $activeField = 'active', bool $displayMultipleLines = false) : self
    {
        $this->pivotActiveField = $activeField;
        return $this->displayInMultipleLines($displayMultipleLines);
    }
    
    public function withEdit(?bool $edit = true) : self
    {
        $this->withEdit = $edit;
        return $this;
    }

    public function canEdit() : bool
    {
        return count($this->pivotFields) !== 0 && $this->withEdit;
    }
    
    public function sortRelatedBy(string $field, string $order = 'asc') : self
    {
        $this->relatedSortField     = $field;
        $this->relatedSortOrder     = $order;
        return $this;
    }

    protected function activeAttributes($child): string
    {
        if(!$this->pivotActiveField || $child->pivot->{$this->pivotActiveField}) {
            return '';
        }
        return "style='color:red; opacity:0.5; text-decoration-line:line-through;'";
    }
}
