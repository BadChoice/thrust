<div class='has-tooltip cursor max-w-sm {{$field->getSortableHeaderClass()}}'>
    @if ($tooltip = $field->getTooltip())
        <span class='tooltip rounded shadow-lg p-2 text-xs bg-black text-white mt-7 normal-case mt-24'> {{ $tooltip  }}</span>
    @endif
    @if ($field->sortableInIndex() && !request('search'))
        @if(request('sort') == $field->field)
             @if(strtolower(request('sort_order')) == 'asc')
                <a class="rounded bg-gray-200 p-1" href='{{ BadChoice\Thrust\ResourceFilters\Sort::link($field->field, 'asc')}}'  class='sortDown'> {{ $field->getTitle(true) }} ▼</a>
            @else
            <a class="rounded bg-gray-200 p-1" href='{{ BadChoice\Thrust\ResourceFilters\Sort::link($field->field, 'desc')}}' class='sortUp'> {{ $field->getTitle(true) }} ▲</a>
            @endif
        @else
            <a href='{{ BadChoice\Thrust\ResourceFilters\Sort::link($field->field, 'asc')}}' class='sortUp'>{{ $field->getTitle(true) }}</a>
        @endif
    @else
        <div style="@if($field->getTooltip()) text-decoration:underline dotted; cursor:pointer; @endif">
            {{ $field->getTitle(true) }}
        </div>
    @endif
</div>