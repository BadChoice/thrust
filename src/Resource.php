<?php

namespace BadChoice\Thrust;

abstract class Resource{

    protected $class;
    protected $paginate = 25;

    abstract public function getFields();

    public function getRows(){
        return ($this->class)::paginate($this->paginate);
    }

    public function find($id)
    {
        return ($this->class)::find($id);
    }

    public function update($id, $newData)
    {
        return $this->find($id)->update($newData);
    }

    public function delete()
    {
        return ($this->class)::delete($id);
    }

}