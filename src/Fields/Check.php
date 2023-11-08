<?php

namespace BadChoice\Thrust\Fields;

use BadChoice\Thrust\Facades\Thrust;

class Check extends Text
{
    protected $withLink = true;
    protected $withLinkPolicyAction = null;
    protected $asSwitch = false;
    public $rowClass    = 'action';

    public function withLink($withLink = true, $policyAction = null)
    {
        $this->withLink = $withLink;
        $this->withLinkPolicyAction = $policyAction;
        return $this;
    }

    public function asSwitch($asSwitch = true)
    {
        $this->asSwitch = $asSwitch;
        return $this;
    }

    public function displayInIndex($object)
    {
        $resourceName = Thrust::resourceNameFromModel($object);
        $resource = Thrust::make($resourceName);
        return view('thrust::fields.checkIndex', [
            'resourceName' => $resourceName,
            'value'        => $object->{$this->field},
            'id'           => $object->id,
            'field'        => $this->field,
            'withLinks'    => $this->shouldShowLinks($resource, $object),
            'asSwitch'     => $this->asSwitch,
            'description'  => $this->getDescription()
        ])->render();
    }

    public function displayInEdit($object, $inline = false)
    {
        return view('thrust::fields.check', [
            'title'  => $this->getTitle(),
            'field'  => $this->field,
            'value'  => $this->getValue($object),
            'inline' => $inline,
            'description' => $this->getDescription(),
        ])->render();
    }

    protected function shouldShowLinks($resource, $object): bool
    {
        if (!$this->withLink) { return false; }
        if (!$this->withLinkPolicyAction) { return true; }
        return $resource->can($this->withLinkPolicyAction, $object);
    }
}
