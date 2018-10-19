<?php

namespace BadChoice\Thrust\ResourceFilters;

class Filters
{
    public static function applyFromRequest($query, $filtersEncoded)
    {
        static::decodeFilters($filtersEncoded)->reject(function ($value){
            return !$value || $value == "--";
        })->each(function($value, $filterClass) use ($query){
            $filter = new $filterClass;
            $filter->apply(request(), $query, $value);
        });
        return $query;
    }

    public static function decodeFilters($filtersEncoded)
    {
        $filters = collect(explode("&", base64_decode($filtersEncoded)));
        return $filters->mapWithKeys(function($filterClass){
            $filterAndValue = explode("=", $filterClass);
            return [urldecode($filterAndValue[0]) => $filterAndValue[1]];
        });
    }
}