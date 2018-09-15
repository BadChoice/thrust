<?php

namespace BadChoice\Thrust;

use BadChoice\Thrust\Actions\MainAction;
use BadChoice\Thrust\Actions\Delete;
use BadChoice\Thrust\Contracts\FormatsNewObject;
use BadChoice\Thrust\Contracts\Prunable;
use BadChoice\Thrust\Fields\Panel;
use BadChoice\Thrust\Fields\Relationship;
use BadChoice\Thrust\ResourceFilters\Search;
use BadChoice\Thrust\ResourceFilters\Sort;
use Illuminate\Database\Query\Builder;

abstract class ChildResource extends Resource{

    public static $parentRelation;
    protected $parentId;

    public function __construct()
    {
        $this->parentId = request('parent_id');
    }

    public function parentId($parentId)
    {
        $this->parentId = $parentId;
        return $this;
    }

    protected function getBaseQuery()
    {
        $query = parent::getBaseQuery();
        return $query->where($this->parentForeignKey(), $this->parentId);
    }

    public function parentForeignKey(){
        return (new static::$model)->{static::$parentRelation}()->getForeignKey();
    }

    public function parent($object)
    {
        if (is_numeric($object)){
           return  (new static::$model)->{static::$parentRelation}()->getRelated()->query()->find($object);
        }
        return $object->{static::$parentRelation};
    }

}