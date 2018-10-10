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

    /** //TODO: improved search by words, fer-ho per el primer fields nomes?
    collect(explode(' ', $text))->each(function ($word) use (&$query) {
            $query->where(function ($subQuery) use ($word) {
                $subQuery->where('products.name', 'like', "%{$word}%");
            });
        });
     */
}