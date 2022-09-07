<?php

namespace BadChoice\Thrust\Html;

use BadChoice\Thrust\ChildResource;
use BadChoice\Thrust\Fields\Field;
use BadChoice\Thrust\Fields\Hidden;
use BadChoice\Thrust\Fields\ParentId;
use BadChoice\Thrust\Resource;
use Illuminate\Support\Str;

class Edit
{
    protected $resource;
    protected $resourceName;

    public function __construct(Resource $resource, $resourceName = null)
    {
        $this->resource     = $resource;
        $this->resourceName = $resourceName;
    }

    public function getEditFields($multiple = false)
    {
        $fields = collect($this->resource->getFields())->filter(function ($field) {
            return $field->showInEdit && $this->resource->can($field->policyAction);
        });
        if ($multiple) {
            $fields = $fields->reject(function ($field) {
                return $field->excludeOnMultiple;
            });
        }
        if ($this->resource::$sortable) {
            $fields->prepend(Hidden::make($this->resource::$sortField));
        }
        if ($this->resource instanceof ChildResource) {
            $fields->prepend(ParentId::make($this->resource->parentForeignKey()));
        }
        return $fields;
    }

    public function getIndexFields(?bool $inline = false)
    {
        return $this->resource->fieldsFlattened($inline)->where('showInIndex', true);
    }

    public function getEditInlineFields()
    {
        return $this->getIndexFields(true);
    }

    public function show($id, $fullPage = false, $multiple = false)
    {
        view()->share('fullPage', $fullPage);
        $object = is_numeric($id) ? $this->resource->find($id) : $id;
        return view('thrust::edit', [
            'title'                     => $this->resource->getTitle(),
            'nameField'                 => $this->resource->nameField,
            'resourceName'              => $this->resourceName ? : $this->resource->name(),
            'fields'                    => $this->getEditFields($multiple),
            'object'                    => $object,
            'hideVisibility'            => Field::getPanelHideVisibilityJson(collect($this->resource->panels($object))),
            'showVisibility'            => Field::getPanelShowVisibilityJson(collect($this->resource->panels($object))),
            'fullPage'                  => $fullPage,
            'updateConfirmationMessage' => $this->resource->getUpdateConfirmationMessage(),
            'multiple'                  => $multiple
        ])->render();
    }

    public function showInline($id)
    {
        $object = is_numeric($id) ? $this->resource->find($id) : $id;
        return view('thrust::editInline', [
            'nameField'     => $this->resource->nameField,
            'resourceName'  => $this->resource->name(),
            'fields'        => $this->getEditInlineFields(),
            'sortable'      => $this->resource::$sortable,
            'object'        => $object,
        ])->render();
    }

    public function showBelongsToManyInline($id, $belongsToManyField)
    {
        $object = is_numeric($id) ? $this->resource->find($id) : $id;
        $relation = Str::singular($belongsToManyField->field);
        return view('thrust::belongsToManyEditInline', [
            'nameField'          => $this->resource->nameField,
            'resourceName'       => $this->resource->name(),
            'fields'             => $this->getEditInlineFields(),
            'sortable'           => $this->resource::$sortable,
            'object'             => $object,
            'belongsToManyField' => $belongsToManyField,
            'relatedName' => $object->{$relation}?->{$belongsToManyField->relationDisplayField},
        ])->render();
    }
}
