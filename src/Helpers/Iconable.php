<?php

namespace BadChoice\Thrust\Helpers;

trait Iconable {

    public $icon = null;

    public function getIcon(){
        return $this->icon ? icon($this->icon) : '';
	}

}