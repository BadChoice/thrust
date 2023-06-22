<?php

namespace BadChoice\Thrust;

use BadChoice\Thrust\Facades\Thrust;

abstract class ChildResource extends Resource
{
    public static $parentRelation;
    public static $parentChildsRelation;
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

    protected function applySearch(&$query)
    {
        if($this->parentId) return;

        parent::applySearch($query);
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

    public function getParentHasManyUrlParams($object)
    {
        $parent = $this->parent($object);
        return $this::$parentChildsRelation && $parent ? [Thrust::resourceNameFromModel($parent), $parent->id, static::$parentChildsRelation] : null; 
    }

    public function editTitle(mixed $object): ?string
    {
        $parent = $this->parent($object);
        $path = $parent
            ? Thrust::make(Thrust::resourceNameFromModel($parent))->editTitle($parent) . ' / '
            : '';
        return $path . parent::editTitle($object);
    }
}
