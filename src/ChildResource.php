<?php

namespace BadChoice\Thrust;

abstract class ChildResource extends Resource
{
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
        if ($this->parentId) {
            $query->where($this->parentForeignKey(), $this->parentId);
        }
        return $query;
    }

    public function parentForeignKey()
    {
        $relation = (new static::$model)->{static::$parentRelation}();
        if (method_exists($relation, 'getForeignKey')) {
            return $relation->getForeignKey();
        }
        return $relation->getForeignKeyName();
    }

    public function parent($object)
    {
        if (is_numeric($object)) {
            return  (new static::$model)->{static::$parentRelation}()->getRelated()->query()->find($object);
        }
        return $object->{static::$parentRelation};
    }
}
