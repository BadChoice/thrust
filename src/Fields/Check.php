<?php

namespace BadChoice\Thrust\Fields;


use BadChoice\Thrust\ResourceManager;

class Check extends Text
{
    protected $withLink = false;
    protected $asSwitch = false;
    public $rowClass = 'action';

    public function withLink($withLink = true){
        $this->withLink = $withLink;
        return $this;
    }

    public function asSwitch($asSwitch = true){
        $this->asSwitch = $asSwitch;
        return $this;
    }

    public function displayInIndex($object)
    {
        return view('thrust::fields.checkIndex',[
            "resourceName" => app(ResourceManager::class)->resourceNameFromModel($object),
            "value" => $object->{$this->field},
            "id" => $object->id,
            "field" => $this->field,
            "withLinks" => $this->withLink,
            "asSwitch" => $this->asSwitch
        ]);
    }

    public function displayInEdit($object, $inline = false)
    {
        return view('thrust::fields.check',[
            'id'    => $object->id,
            'title' => $this->getTitle(),
            'field' => $this->field,
            'value' => $this->getValue($object),
            'inline' => $inline,
        ]);
    }

}