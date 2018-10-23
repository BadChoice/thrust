<?php

namespace BadChoice\Thrust\Exporter;

use BadChoice\Thrust\Resource;

class CSVExporter
{
    protected $output = "";
    protected $indexFields;

    public function export(Resource $resource)
    {
        $this->indexFields = $resource->fieldsFlattened()->where('showInIndex', true);
        $this->writeHeader();
        $resource->query()->chunk(200, function($rows) use(&$output, $resource){
            $rows->each(function($row){
                $this->writeRow($row);
            });
        });
        return response(rtrim($this->output, "\n"), 200, $this->getHeaders($resource->name()));
    }

    private function getHeaders($title)
    {
        return [
            'Content-Type'        => 'application/csv; charset=UTF-8',
            'Content-Encoding'    => 'UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $title . '.csv"',  // Safari filename must be between commas
        ];
    }

    private function writeHeader()
    {
        $this->indexFields->each(function ($field) {
            $this->output .= $field->field . ";";
        });
        $this->output .= PHP_EOL;
    }

    private function writeRow($row)
    {
        $this->indexFields->each(function($field) use($row){
            $this->output .= $field->getValue($row) .";";
        });
        $this->output .= PHP_EOL;
    }
}