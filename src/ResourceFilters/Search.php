<?php

namespace BadChoice\Thrust\ResourceFilters;

class Search
{
    public static function apply($query, $searchText, $searchFields) {
        $searchFields = collect($searchFields);
        $firstField = $searchFields->shift();
        static::applyField($query, $firstField, $searchText);

        return $query->orWhere(function ($query) use($searchText, $searchFields){
            return $searchFields->reduce(function($query, $searchField) use($searchText) {
                return $query->orWhere($searchField, 'like', "%{$searchText}%");
            }, $query);
        });
    }


    /**
     * This does a super search by separating the word by spaces
     * @param $query
     * @param $field
     * @param $text
     * @return mixed
     */
    public static function applyField(&$query, $field, $text)
    {
        collect(explode(' ', $text))->each(function ($word) use ($field, &$query) {
            $query->where(function ($subQuery) use ($word, $field) {
                $subQuery->where($field, 'like', "%{$word}%");
            });
        });
        return $query;
    }
}