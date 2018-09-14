<?php

namespace BadChoice\Thrust\Fields;


use BadChoice\Thrust\ResourceFilters\Search;

abstract class Relationship extends Field
{
    public $relationDisplayField = 'name';
    public $relatedScope          = null;
    public $searchFields          = null;

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

    public function relatedScope($relatedScope)
    {
        $this->relatedScope = $relatedScope;
        return $this;
    }

    public function relatedQuery($object, $allowDuplicates = true){
        $query = $this->getRelation($object)->getRelated()->query();

        if ($this->relatedScope)
            $query = call_user_func($this->relatedScope, $query);

        if (! $allowDuplicates)
            $query->whereNotIn('id', $this->getValue($object)->pluck('id')->toArray());

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
       $query->select('id', $this->relationDisplayField)->limit(10);

        return $query;
    }

}