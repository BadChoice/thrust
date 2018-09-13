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

    public static $parentField;
    protected $parentId;

    public function parentId($parentId)
    {
        $this->parentId = $parentId;
        return $this;
    }

    protected function getBaseQuery()
    {
        $query = parent::getBaseQuery();
        return $query->where(static::$parentField, $this->parentId);
    }

}