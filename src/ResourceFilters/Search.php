<?php

namespace BadChoice\Thrust\ResourceFilters;

class Search
{
    public static function apply($query, $searchText, $searchFields)
    {
        $searchFields = collect($searchFields);
        $firstField   = $searchFields->shift();
        static::applyField($query, $firstField, $searchText);

        return $query->orWhere(function ($query) use($searchText, $searchFields){
            return $searchFields->reduce(function($query, $searchField) use($searchText) {
                return static::applyFieldSimple($query, $searchField, $searchText);
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

    public static function applyFieldSimple(&$query, $searchField, $searchText){
        if (str_contains($searchField, '.')) {
            return self::applyRelationshipField($query, $searchField, $searchText);
        }
        return $query->orWhere($searchField, 'like', "%{$searchText}%");
    }

    /**
     * @param $query
     * @param $searchField
     * @param $searchText
     * @return mixed
     */
    public static function applyRelationshipField(&$query, $searchField, $searchText)
    {
        [$relationship, $relationshipField] = explode('.', $searchField);

        return $query->orWhereHas($relationship, function ($query) use ($relationshipField, $searchText) {
            return $query->where($relationshipField, 'like', "%{$searchText}%");
        });
    }
}
