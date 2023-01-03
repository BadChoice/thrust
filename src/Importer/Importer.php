<?php

namespace BadChoice\Thrust\Importer;

use BadChoice\Thrust\Resource;
use Illuminate\Support\Facades\DB;

class Importer
{
    public function __construct(public string $csv, public Resource $resource)
    {
    }

    public function fields() : array
    {
        return collect(explode(";", collect(explode("\n", $this->csv))->first()))->filter()->all();
    }

    public function import($mapping) : int
    {
        $rowsMapped = $this->rowsMapped($mapping);

        DB::transaction(function() use($rowsMapped){
            foreach($rowsMapped as $data) {
                $this->resource->updateOrCreate($data);
            }
        });
        return count($rowsMapped);
        //TODO:  default values
        //TODO:  validate
        //TODO: update if there is ID
        //TODO: Show row count on importer.show
    }

    public function rowsMapped($mapping) : array
    {
        $rows = explode("\n", $this->csv);
        $fields = $this->fields();
        $mapping = collect($mapping)->filter()->mapWithKeys(function($csvField, $thrustField) use($fields){
           return [$thrustField => array_flip($fields)[$csvField]];
        });
        return collect($rows)->splice(1)->map(function($row) use($mapping){
            $row = explode(";", $row);
            return $mapping->mapWithKeys(function($index, $field) use($row){
                return [$field => $row[$index]];
            })->all();
        })->all();
    }
}