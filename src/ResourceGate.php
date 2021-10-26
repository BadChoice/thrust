<?php

namespace BadChoice\Thrust;

use BadChoice\Thrust\Facades\Thrust;
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
        $valid = true;
        try{
            $resource = Thrust::make($resource);
            if ($resource::$gate) {
                $valid = auth()->user()->can($resource::$gate);
            }
            $policy = $this->policyFor($resource);
            if ($policy) {
                if (!(new $policy())->$ability(auth()->user(), $object ?? $resource::$model)){
                    $valid = false;
                    throw new AuthorizationException("This action is unauthorized.");
                }
            }
        } catch(\Exception $e) {}
        return $valid;
    }

    public function policyFor($resource){
        return $resource::$policy ?? Gate::getPolicyFor($resource::$model);
    }
}
