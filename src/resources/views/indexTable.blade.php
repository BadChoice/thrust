@include('thrust::components.paginator', ["data" => $rows])
@if (count($rows) > 0)
    @include('thrust::components.tableDensity')
    <table class="thrust-table list striped">
        <thead class="sticky">
            <th class="hide-mobile">
                <input type="checkbox" onclick="toggleSelectAll(this)">
            </th>
            @if ($sortable)
                <th class="hide-mobile">  </th>
            @endif
            @foreach($fields as $field)
                <th class="{{$field->rowClass}}">
                    <div class='{{$field->getSortableHeaderClass()}}'>
                    @if ($field->sortableInIndex() && !request('search'))
                        @if(request('sort') == $field->field)
                            @if(strtolower(request('sort_order')) == 'asc')
                                <a class="rounded bg-gray-200 p-1" href='{{ BadChoice\Thrust\ResourceFilters\Sort::link($field->field, 'desc')}}'  class='sortDown'> {{ $field->getTitle(true) }} ▼</a>
                            @else
                                <a class="rounded bg-gray-200 p-1" href='{{ BadChoice\Thrust\ResourceFilters\Sort::link($field->field, 'asc')}}' class='sortUp'> {{ $field->getTitle(true) }} ▲</a>
                            @endif
                        @else
                            <a href='{{ BadChoice\Thrust\ResourceFilters\Sort::link($field->field, 'asc')}}' class='sortUp'>{{ $field->getTitle(true) }}</a>
                        @endif
                    @else
                        {{ $field->getTitle(true) }}
                    @endif
                    </div>
                </th>
            @endforeach
{{--            <th class="action text-right" colspan="2" >--}}
{{--            </th>--}}
        </thead>

        <tbody class="@if($sortable) sortable @endif">
        @foreach ($rows as $row)
            <tr id="sort_{{$row->id}}">
                <td class="action hide-mobile"><input class='actionCheckbox' type="checkbox" name="selected[{{$row->id}}]" meta:id="{{$row->id}}"></td>
                @if ($sortable)
                    <td class="sort action hide-mobile"></td>
                @endif
                @foreach($fields as $field)
                    <td class="{{$field->rowClass}}">
                        @if (! $field->shouldHide($row, 'index') && $field->shouldShow($row, 'index') && $resource->can($field->policyAction, $row))
                            {!! $field->displayInIndex($row) !!}
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
    @include('thrust::components.paginator',["data" => $rows])
@else
    @include('thrust::components.noData')
@endif