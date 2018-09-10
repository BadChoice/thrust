<?php

namespace BadChoice\Thrust;

use BadChoice\Thrust\Actions\Action;
use BadChoice\Thrust\Contracts\FormatsNewObject;
use BadChoice\Thrust\Fields\Panel;
use BadChoice\Thrust\Fields\Relationship;
use BadChoice\Thrust\ResourceFilters\Search;
use BadChoice\Thrust\ResourceFilters\Sort;
use Illuminate\Database\Query\Builder;

abstract class Resource{

    /**
     * @var string defines the underlying model class
     */
    public static $model;

    /**
     * @var string The field that will be used to display the resource main name
     */
    public $nameField = 'name';

    /**
     * Defines de number of items to paginate
     */
    protected $pagination = 25;

    /**
     * Defines the searchable fields
     */
    public static $search = [];

    /**
     * @var bool define if the resource is sortable and can be arranged in the index view
     */
    public static $sortable = false;
    public static $sortField = 'order';

    /**
     * @var array Set the default eager loading relationships
     */
    protected $with = [];

    protected $query = null;

    /**
     * @return array array of fields
     */
    abstract public function fields();

    public function fieldsFlattened()
    {
        return collect($this->fields())->map(function($field){
            if ($field instanceof Panel) return $field->fields;
            return $field;
        })->flatten();
    }

    public function setQuery($query){
        $this->query = $query;
        return $this;
    }


    /**
     * @return Builder
     */
    public function query()
    {
        $query = $this->query ?? static::$model::query()->with($this->getWithFields());
        if (request('search')){
            Search::apply($query, request('search'), static::$search);
        }
        if (static::$sortable){
            Sort::apply($query, static::$sortField, 'ASC');
        }else if (request('sort')){
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

    public function count()
    {
        return (static::$model)::count();
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

    public function makeNew()
    {
        $object = new static::$model;
        if (collect(class_implements($this))->contains(FormatsNewObject::class)){
            $this->formatNewObject($object);
        }

        if (static::$sortable) {
            $object->{static::$sortField} = static::$model::count();
        }
        return $object;
    }

    public function getValidationRules($objectId)
    {
        $fields = $this->fieldsFlattened()->where('showInEdit',true);
        return $fields->mapWithKeys(function($field) use($objectId){
           return [$field->field => str_replace("{id}", $objectId, $field->validationRules)];
        })->filter(function($value){
            return $value != null;
        })->toArray();
    }

    public function actions()
    {
        return [
            Action::make('new'),
        ];
    }

    public function getWithFields()
    {
        return count($this->with) ? $this->with : $this->getRelationshipsFields()->toArray();
    }

    public function getRelationshipsFields(){
        return $this->fieldsFlattened()->filter(function($field){
            return $field instanceof Relationship;
        })->pluck('field');
    }

}