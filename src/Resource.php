<?php

namespace BadChoice\Thrust;

abstract class Resource{

    public static $model;
    protected $paginate = 25;

    abstract public function fields();

    public function getRows(){
        return (static::$model)::paginate($this->paginate);
    }

    public function name(){
        return app(ResourceManager::class)->resourceNameFromModel(static::$model);
    }

    public function find($id)
    {
        return (static::$model)::find($id);
    }

    public function update($id, $newData)
    {
        return $this->find($id)->update($newData);
    }

    public function delete()
    {
        return (static::$model)::delete($id);
    }

}