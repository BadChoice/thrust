<?php

namespace BadChoice\Thrust\ResourceFilters;

class Search
{
    public static function apply($query, $searchText, $searchFields)
    {
        $searchFields = collect($searchFields);
        $firstField   = $searchFields->shift();
        static::applyField($query, $firstField, $searchText);

        return $query->orWhere(function ($query) use ($searchText, $searchFields) {
            return $searchFields->reduce(function ($query, $searchField) use ($searchText) {
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

    public static function applyFieldSimple($query, $field, $text)
    {
        if (str_contains($field, '.')) {
            return static::applyRelationshipField($query, $field, $text);
        }

        return $query->orWhere($field, 'like', "%{$text}%");
    }

    //TODO: need to work a bit more with this
    public static function applyRelationshipField($query, $field, $text)
    {
        return $query;
        [$relationShip, $field] = explode('.', $field);
        $tableToJoin            = $query->getModel()->$relationShip()->getModel()->getTable();

        $q = $query->leftJoin($tableToJoin, function ($join) use ($relationShip, $field, $text, $tableToJoin, $query) {
            $join->on($tableToJoin .'.'. $query->getModel()->$relationShip()->getOwnerKey(), '=', $query->getModel()->getTable().'.'.$query->getModel()->$relationShip()->getForeignKey())
                ->where($tableToJoin.'.' . $field, 'like', "%$text%");
        });
        //dd($q->toSql() ,$q->getBindings());
    }
}
