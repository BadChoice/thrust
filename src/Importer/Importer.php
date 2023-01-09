<?php

namespace BadChoice\Thrust\Importer;

use BadChoice\Thrust\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Importer
{
    public function __construct(public string $csv, public Resource $resource)
    {
    }

    public function fields() : array
    {
        $firstLine = explode("\n", $this->csv, 2)[0] ?? '';
        $columns = explode(';', $firstLine);
        $columns = array_map(fn($column) => str_replace(["\r","\n"], "", $column), $columns);
        return array_filter($columns);
    }

    public function import($mapping) : int
    {
        $rowsMapped = $this->rowsMapped($mapping);
        $rules = $this->resource->getValidationRules(null);
        DB::transaction(function() use($rowsMapped, $rules){
            foreach ($rowsMapped as $data) {
                Validator::make($data, $rules)->stopOnFirstFailure()->validate();
                $this->resource->updateOrCreate($data);
            }
        });
        return count($rowsMapped);
        //TODO:  default values
    }

    public function rowsMapped($mapping) : array
    {
        $fields = $this->fields();
        $mapping = collect($mapping)->filter()->mapWithKeys(function($csvField, $thrustField) use($fields){
           return [$thrustField => array_flip($fields)[$csvField]];
        });
        return collect($this->rows())->map(function($row) use($mapping){
            $row = explode(";", $row);
            return $mapping->mapWithKeys(function($index, $field) use($row){
                return [$field => $row[$index]];
            })->all();
        })->all();
    }

    public function rows() : array
    {
        return collect(explode("\n", $this->csv))->splice(1)->all();
    }
}