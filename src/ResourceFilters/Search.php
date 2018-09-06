<?php

namespace BadChoice\Thrust\ResourceFilters;


class Search
{
    public static function apply($query, $searchText, $searchFields) {
        return $query->where(function ($query) use($searchText, $searchFields){
            return collect($searchFields)->reduce(function($query, $searchField) use($searchText) {
                return $query->orWhere($searchField, 'like', "%{$searchText}%");
            }, $query);
        });
    }
}