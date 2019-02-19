<?php

namespace BadChoice\Thrust;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

class ResourceGate
{
    use AuthorizesRequests;

    public function check($resource, $ability, $object = null)
    {
        if (! $this->can($resource, $ability, $object)) {
            throw new AuthorizationException('This action is unauthorized.');
        }
    }

    public function can($resource, $ability, $object = null)
    {
        $valid    = true;
        $resource = app(ResourceManager::class)->make($resource);
        if ($resource::$gate) {
            $valid = auth()->user()->can($resource::$gate);
        }
        $policy = Gate::getPolicyFor($resource::$model);
        if ($policy) {
            $valid = auth()->user()->can($ability, $object ?? $resource::$model) && $valid;
        }
        return $valid;
    }
}
