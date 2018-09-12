<?php

namespace BadChoice\Thrust\Contracts;

/**
 * It means the filed can have something attached that can be deleted when the resource is deleted
 * Interface Prunable
 * @package BadChoice\Thrust\Contracts
 */
interface Prunable{
    public function prune($object);
}