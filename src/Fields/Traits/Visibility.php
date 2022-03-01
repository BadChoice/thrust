<?php

namespace BadChoice\Thrust\Fields\Traits;


use BadChoice\Thrust\Fields\FieldContainer;

trait Visibility
{
    public $visibilities = [];

    function __get($value){
        if(! isset($this->visibilities[$value])){
            $class = "BadChoice\Thrust\Visibility\\".ucfirst(substr($value, 0, 4));
            $this->visibilities[$value] = new $class();
	    }
        return $this->visibilities[$value];
    }

    function hideWhen($field, $values = [true], $where = null){
        $values = collect($values)->all();
        if ($where == null || $where == 'index')
            $this->hideIndex->hideWhen($field, $values);
        if ($where == null || $where == 'edit')
            $this->hideEdit->hideWhen($field, $values);

        return $this;
    }

    function showWhen($field, $values = [true], $where=null){
        $values = collect($values)->all();
        if ($where == null || $where == 'index')
            $this->showIndex->showWhen($field, $values);
        if ($where == null || $where == 'edit')
            $this->showEdit->showWhen($field, $values);

        return $this;
    }

    function hideCallback($callback, $where=null){
        if ($where == null || $where == 'index')
            $this->hideIndex->hideCallback($callback);
        if ($where == null || $where == 'edit')
            $this->hideEdit->hideCallback($callback);

        return $this;
    }

    function showCallback($callback, $where=null){
        if ($where == null || $where == 'index')
            $this->showIndex->showCallback($callback);
        if ($where == null || $where == 'edit')
            $this->showEdit->showCallback($callback);

        return $this;
    }

    function shouldHide($object, $where=null){
        if ($where == 'index')
            return $this->hideIndex->shouldHide($object);
        if ($where == null || $where == 'edit')
            return $this->hideEdit->shouldHide($object);

        return $this->hideEdit->shouldHide($object) && $this->hideIndex->shouldHide($object);
    }

    function shouldShow($object, $where=null){
        if ($where == 'index')
            return $this->showIndex->shouldShow($object);
        if ($where == null || $where == 'edit')
            return $this->showEdit->shouldShow($object);

        return $this->showEdit->shouldShow($object) && $this->showIndex->shouldShow($object);
    }

    static function getPanelShowVisibilityJson($resource){
        $fieldsVisibility = collect($resource->panels())->flatMap(function($panel) {
            return collect($panel->fields)->filter(function ($field) {
                if ($field instanceof FieldContainer) { return false ;}
                return $field->showEdit->field != null;
            });
        })->mapWithKeys(function ($field) {
            return [$field->field => [
                'field' =>  $field->showEdit->field,
                'values' => $field->showEdit->values]
            ];
        });
        $panelVisibility = collect($resource->panels())->filter(function ($panel) {
            return $panel->showEdit->field != null;
        })->mapWithKeys(function ($panel) {
            return ['panel_' . $panel->getId() => [
                'field' => $panel->showEdit->field,
                'values' => $panel->showEdit->values]
            ];
        });
        return $fieldsVisibility->merge($panelVisibility);
    }


    static function getPanelHideVisibilityJson($resource)
    {
        $fieldsVisibility = collect($resource->panels())->flatMap(function($panel) {
            return collect($panel->fields)->filter(function ($field) {
                if ($field instanceof FieldContainer) { return false ;}
                return $field->hideEdit->field != null;
            });
        })->mapWithKeys(function ($field) {
            return [$field->field => [
                'field' =>  $field->hideEdit->field,
                'values' => $field->hideEdit->values]
            ];
        });
        $panelVisibility =  collect($resource->panels())->filter(function ($panel) {
            return $panel->hideEdit->field != null;
        })->mapWithKeys(function ($panel) {
            return ['panel_' . $panel->getId() => [
                'field' =>  $panel->hideEdit->field,
                'values' => $panel->hideEdit->values]
            ];
        });
        return $fieldsVisibility->merge($panelVisibility);
    }
}
