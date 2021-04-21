<?php

use Illuminate\Support\Collection;

Collection::macro('firstMap', function($callback){
    $result = null;
    $this->first(function($element) use (&$result, $callback) {
        return $result = $callback($element);
    });
    return $result;
});