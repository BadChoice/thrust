<?php

namespace BadChoice\Thrust\ResourceFilters;

class Sort
{
    public static function apply($query, $field, $order)
    {
        return $query->orderBy($field, $order);
    }

    public static function link($field, $order = 'asc')
    {
        return request()->url() . '?' . http_build_query(array_merge(request()->query(), [
                'sort'       => $field,
                'sort_order' => $order
            ]));
    }
}
