<?php

namespace BadChoice\Thrust;

use BadChoice\Thrust\Actions\Delete;
use BadChoice\Thrust\Actions\MainAction;
use BadChoice\Thrust\Contracts\FormatsNewObject;
use BadChoice\Thrust\Contracts\Prunable;
use BadChoice\Thrust\Fields\Panel;
use BadChoice\Thrust\Fields\Relationship;
use BadChoice\Thrust\ResourceFilters\Search;
use BadChoice\Thrust\ResourceFilters\Sort;

abstract class Resource{

    /**
     * @var string defines the underlying model class
     */
    public static $model;

    /**
     * Set this to true when the resource is a simple one like business
     * @var bool
     */
    public static $singleResource = false;

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
     * @var Defines the global gate ability for the actions to be performed,
     * It goes along with the default Laravel resource Policy if any
     */
    public static $gate;


    /**
     * @var bool define if the resource is sortable and can be arranged in the index view
     */
    public static $sortable     = false;
    public static $sortField    = 'order';
    public static $defaultSort  = 'id';

    /**
     * @var array Set the default eager loading relationships
     */
    protected $with = [];

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

    public function fieldFor($field)
    {
        return $this->fieldsFlattened()->where('field', $field)->first();
    }

    public function panels()
    {
        return collect($this->fields())->filter(function($field){
            return ($field instanceof Panel);
        });
    }

    public function name()
    {
        return app(ResourceManager::class)->resourceNameFromModel(static::$model);
    }

    public function find($id)
    {
        return (static::$model)::find($id);
    }

    public function first()
    {
        return $this->getBaseQuery()->first();
    }

    public function count()
    {
        return $this->getBaseQuery()->count();
    }

    public function update($id, $newData)
    {
        $object = $this->find($id);
        app(ResourceGate::class)->check($this, 'update', $object);
        return $object->update($newData);
    }

    public function delete($id)
    {
        $object = is_numeric($id) ? $this->find($id) : $id;
        app(ResourceGate::class)->check($this, 'delete', $object);
        $this->prune($object);
        return $object->delete();
    }

    public function canEdit($object)
    {
        return app(ResourceGate::class)->can($this, 'update', $object);
    }

    public function canDelete($object)
    {
        return app(ResourceGate::class)->can($this, 'delete', $object);
    }

    public function makeNew()
    {
        $object = new static::$model;
        if (collect(class_implements($this))->contains(FormatsNewObject::class)){
            $this->formatNewObject($object);
        }

        if (static::$sortable) {
            $object->{static::$sortField} = $this->count();
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

    public function mainActions()
    {
        return [
            MainAction::make('new'),
        ];
    }

    public function actions()
    {
        return [
            new Delete
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

    public function prune($object)
    {
        $this->fieldsFlattened()->filter(function ($field) {
            return $field instanceof Prunable;
        })->each->prune($object);
    }

    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    protected function getBaseQuery()
    {
        return $this->query ?? static::$model::query()->with($this->getWithFields());
    }

    /**
     * @return Builder
     */
    public function query()
    {
        $query = $this->getBaseQuery();
        if (request('search')){
            Search::apply($query, request('search'), static::$search);
        }
        if (static::$sortable){
            Sort::apply($query, static::$sortField, 'ASC');
        } else if (request('sort')){
            Sort::apply($query, request('sort'), request('sort_order'));
        }
        else{
            Sort::apply($query, static::$defaultSort, 'ASC');
        }
        return $query;
    }

    public function rows() {
        return $this->query()->paginate($this->pagination);
    }

    public function getDescription()
    {
        $description = trans_choice( config('thrust.translationsDescriptionsPrefix') . str_singular($this->name()), 1);
        if (! str_contains($description, config('thrust.translationsDescriptionsPrefix'))){
            return $description;
        }
        return "";
    }

}