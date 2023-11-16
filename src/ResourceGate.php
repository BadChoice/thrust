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
                $valid = $this->getUser()->can($resource::$gate);
            }
            $policy = $this->policyFor($resource);
            if ($policy) {
                $valid = $this->checkPolicyValidation($resource, $ability, $object, $policy);
            }
        } catch(\Exception $e) {}
        return $valid;
    }

    public function checkPolicyValidation($resource, $ability, $object, $policy): bool
    {
        $policyInstance = new $policy;
        if (method_exists($policyInstance, 'before') && $policyInstance->before($this->getUser(), $ability, $object) !== null) {
            return $policyInstance->before($this->getUser(), $ability, $object);
        }
        return $policyInstance->$ability($this->getUser(), $object ?? $resource::$model);
    }

    public function policyFor($resource){
        return $resource::$policy ?? Gate::getPolicyFor($resource::$model);
    }

    protected function getUser(): mixed
    {
        return auth()->user();
    }
}
