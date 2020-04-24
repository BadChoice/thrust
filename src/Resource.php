<?php

namespace BadChoice\Thrust;

use BadChoice\Thrust\Actions\Delete;
use BadChoice\Thrust\Actions\MainAction;
use BadChoice\Thrust\Contracts\FormatsNewObject;
use BadChoice\Thrust\Contracts\Prunable;
use BadChoice\Thrust\Fields\Edit;
use BadChoice\Thrust\Fields\Panel;
use BadChoice\Thrust\Fields\Relationship;
use BadChoice\Thrust\ResourceFilters\Filters;
use BadChoice\Thrust\ResourceFilters\Search;
use BadChoice\Thrust\ResourceFilters\Sort;
use Illuminate\Support\Str;

abstract class Resource
{

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
    public $pagination = 25;

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
    public static $defaultOrder = 'ASC';

    /**
     * @var array Set the default eager loading relationships
     */
    protected $with = null;

    /**
     * @var Collection where rows already fetched are stored
     */
    private $alreadyFetchedRows;

    /**
     * @return array array of fields
     */
    abstract public function fields();

    public function getFields(){
        return array_merge(
            $this->fields(),
            $this->editAndDeleteFields()
        );
    }

    public function fieldsFlattened()
    {
        return collect($this->getFields())->map(function ($field) {
            if ($field instanceof Panel) {
                return $field->fields;
            }
            return $field;
        })->flatten();
    }

    public function fieldFor($field)
    {
        return $this->fieldsFlattened()->where('field', $field)->first();
    }

    public function panels()
    {
        return collect($this->fields())->filter(function ($field) {
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

    public function create($data)
    {
        app(ResourceGate::class)->check($this, 'create');
        return static::$model::create($this->mapRequest($data));
    }

    public function update($id, $newData)
    {
        $object = $this->find($id);
        app(ResourceGate::class)->check($this, 'update', $object);
        return $object->update($this->mapRequest($newData));
    }

    public function delete($id)
    {
        $object = is_numeric($id) ? $this->find($id) : $id;
        app(ResourceGate::class)->check($this, 'delete', $object);
        $this->canBeDeleted($object);
        $this->prune($object);
        return $object->delete();
    }

    protected function canBeDeleted($object)
    {
        if (method_exists($object, 'canBeDeleted') && ! $object::canBeDeleted($object->id)) {
            throw new CanNotDeleteException(__('admin.cantDelete'));
        }
        return true;
    }

    public function canEdit($object)
    {
        return $this->can('update', $object);
    }

    public function canDelete($object)
    {
        return $this->can('delete', $object);
    }

    public function can($ability, $object = null){
        if (! $ability) return true;
        return app(ResourceGate::class)->can($this, $ability, $object);
    }

    public function makeNew()
    {
        $object = new static::$model;
        if (collect(class_implements($this))->contains(FormatsNewObject::class)) {
            $this->formatNewObject($object);
        }

        if (static::$sortable) {
            $object->{static::$sortField} = $this->count();
        }
        return $object;
    }

    public function getValidationRules($objectId)
    {
        $fields = $this->fieldsFlattened()->where('showInEdit', true);
        return $fields->mapWithKeys(function ($field) use ($objectId) {
            return [$field->field => str_replace('{id}', $objectId, $field->validationRules)];
        })->filter(function ($value) {
            return $value != null;
        })->toArray();
    }

    private function mapRequest($data)
    {
        $this->fieldsFlattened()->filter(function ($field) use ($data) {
            return isset($data[$field->field]);
        })->each(function ($field) use (&$data) {
            $data[$field->field] = $field->mapAttributeFromRequest($data[$field->field]);
        });
        return $data;
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

    public function filters()
    {
        return null;
    }

    public function getWithFields()
    {
        return $this->with !== null ? $this->with : $this->getRelationshipsFields()->toArray();
    }

    public function getRelationshipsFields()
    {
        return $this->fieldsFlattened()->filter(function ($field) {
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
        if (request('search')) {
            Search::apply($query, request('search'), static::$search);
        }

        if (static::$sortable) {
            Sort::apply($query, static::$sortField, 'ASC');
        } elseif (request('sort') && $this->sortFieldIsValid(request('sort'))) {
            Sort::apply($query, request('sort'), request('sort_order'));
        } else {
            Sort::apply($query, static::$defaultSort, static::$defaultOrder);
        }

        if (request('filters')) {
            Filters::applyFromRequest($query, request('filters'));
        }
        return $query;
    }

    public function rows()
    {
        if (! $this->alreadyFetchedRows) {
            return $this->fetchRows();
        }
        return $this->alreadyFetchedRows;
    }

    public function getDescription()
    {
        $description = trans_choice(config('thrust.translationsDescriptionsPrefix') . Str::singular($this->name()), 1);
        if (! Str::contains($description, config('thrust.translationsDescriptionsPrefix'))) {
            return $description;
        }
        return '';
    }

    public function filtersApplied()
    {
        if (! request()->has('filters')) {
            return collect();
        }
        return Filters::decodeFilters(request('filters'));
    }

    public function sortFieldIsValid($sort)
    {
        return $this->fieldsFlattened()->where('sortable', true)->pluck('field')->contains($sort);
    }

    protected function editAndDeleteFields()
    {
        return [Edit::make('edit'), Fields\Delete::make('delete')];
    }

    private function fetchRows()
    {
        if (request('search')) {
            $this->alreadyFetchedRows = $this->query()->paginate(200);
        }else {
            $this->alreadyFetchedRows = $this->query()->paginate($this->pagination);
        }
        return $this->alreadyFetchedRows;
    }
}
