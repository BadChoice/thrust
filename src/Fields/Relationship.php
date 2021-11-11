<?php

namespace BadChoice\Thrust\Fields;

use BadChoice\Thrust\Fields\Traits\Searchable;
use BadChoice\Thrust\ResourceFilters\Search;

abstract class Relationship extends Field
{
    use Searchable;

    public $relationDisplayField  = 'name';
    public $relatedScope          = null;
    public $withLink              = false;

    public function withLink($withLink = true)
    {
        $this->withLink = $withLink;
        return $this;
    }

    public function getRelation($object)
    {
        return $object->{$this->field}();
    }

    public function getRelationForeignKey($object)
    {
        $relation = $this->getRelation($object);
        if (method_exists($relation, 'getForeignKey')) {
            return $relation->getForeignKey();
        }
        return $relation->getForeignKeyName();
    }

    public function getOwnerKey($object)
    {
        $relation = $this->getRelation($object);
        if (method_exists($relation, 'getOwnerKey')) {
            return $relation->getOwnerKey();
        }
        return $relation->getOwnerKeyName();
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

    public function relatedScope($relatedScope)
    {
        $this->relatedScope = $relatedScope;
        return $this;
    }

    public function relatedQuery($object, $allowDuplicates = true)
    {
        $query = $this->getRelation($object)->getRelated()->query();

        if ($this->relatedScope) {
            $query = call_user_func($this->relatedScope, $query);
        }

        if (! $allowDuplicates) {
            $query->whereNotIn('id', $this->getValue($object)->pluck('id')->toArray());
        }

        return $query;
    }

    public function searchFields($searchFields)
    {
        $this->searchFields = $searchFields;
        return $this;
    }

    public function searchRelatedQuery($object, $searchText, $allowDuplicates = true)
    {
        $query = $this->relatedQuery($object, $allowDuplicates);

        Search::apply($query, $searchText, $this->searchFields ?? [$this->relationDisplayField]);
        $query->select('id', $this->relationDisplayField)->limit(25);

        return $query;
    }

    public function searchQuery($object, $search)
    {
        return Search::apply($this->getRelation($object), $search, $this->searchFields ?? [$this->relationDisplayField]);
    }

    public function sortableInIndex()
    {
        return false;
    }
}
