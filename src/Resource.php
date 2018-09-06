<?php

namespace BadChoice\Thrust;

use BadChoice\Thrust\ResourceFilters\Search;
use BadChoice\Thrust\ResourceFilters\Sort;
use Illuminate\Database\Query\Builder;

abstract class Resource{

    /**
     * @var string defines the underlying model class
     */
    public static $model;

    /**
     * Defines de number of items to paginate
     */
    protected $pagination = 25;

    /**
     * Defines the searchable fields
     */
    public static $search = [];

    protected $query = null;

    /**
     * @return array array of fields
     */
    abstract public function fields();

    public function setQuery($query){
        $this->query = $query;
        return $this;
    }


    /**
     * @return Builder
     */
    public function query()
    {
        $query = $this->query ?? static::$model::query();
        if (request('search')){
            Search::apply($query, request('search'), static::$search);
        }
        if (request('sort')){
            Sort::apply($query, request('sort'), request('sort_order'));
        }
        return $query;
    }

    public function rows() {
        return $this->query()->paginate($this->pagination);
    }

    public function name()
    {
        return app(ResourceManager::class)->resourceNameFromModel(static::$model);
    }

    public function find($id)
    {
        return (static::$model)::find($id);
    }

    public function update($id, $newData)
    {
        return $this->find($id)->update($newData);
    }

    public function delete()
    {
        return (static::$model)::delete($id);
    }

    public function canEdit($row)
    {
        return true;
    }

    public function canDelete($row)
    {
        return true;
    }

}