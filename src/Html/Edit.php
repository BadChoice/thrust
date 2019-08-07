<?php

namespace BadChoice\Thrust\Html;

use BadChoice\Thrust\ChildResource;
use BadChoice\Thrust\Fields\Hidden;
use BadChoice\Thrust\Fields\ParentId;
use BadChoice\Thrust\Resource;

class Edit
{
    protected $resource;
    protected $resourceName;

    public function __construct(Resource $resource, $resourceName = null)
    {
        $this->resource     = $resource;
        $this->resourceName = $resourceName;
    }

    public function getEditFields()
    {
        $fields = collect($this->resource->fields())->where('showInEdit', true);
        if ($this->resource::$sortable) {
            $fields->prepend(Hidden::make($this->resource::$sortField));
        }
        if ($this->resource instanceof ChildResource) {
            $fields->prepend(ParentId::make($this->resource->parentForeignKey()));
        }
        return $fields;
    }

    public function getIndexFields()
    {
        return $this->resource->fieldsFlattened()->where('showInIndex', true);
    }

    public function getVisiblityJson()
    {
        return $this->resource->panels()->filter(function ($panel) {
            return $panel->hideWhenField != null;
        })->mapWithKeys(function ($panel) {
            return [$panel->getId() => [
                'field' => $panel->hideWhenField,
                'value' => $panel->hideWhenValue]
            ];
        });
    }

    public function show($id, $fullPage = false)
    {
        view()->share('fullPage', $fullPage);
        $object = is_numeric($id) ? $this->resource->find($id) : $id;
        return view('thrust::edit', [
            'nameField'     => $this->resource->nameField,
            'resourceName'  => $this->resourceName ? : $this->resource->name(),
            'fields'        => $this->getEditFields(),
            'object'        => $object,
            'visibility'    => $this->getVisiblityJson(),
            'fullPage'      => $fullPage
        ])->render();
    }

    public function showInline($id)
    {
        $object = is_numeric($id) ? $this->resource->find($id) : $id;
        return view('thrust::editInline', [
            'nameField'     => $this->resource->nameField,
            'resourceName'  => $this->resource->name(),
            'fields'        => $this->getIndexFields(),
            'sortable'      => $this->resource::$sortable,
            'object'        => $object,
        ])->render();
    }
}
