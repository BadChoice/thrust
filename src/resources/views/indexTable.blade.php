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
                    <div class='{{$field->getSortableHeaderClass()}}'>{{ $field->getTitle(true) }}
                    @if ($field->sortable && !request('search'))
                        <div class='sortArrows'>
                            <a href='{{ BadChoice\Thrust\ResourceFilters\Sort::link($field->field, 'desc')}}' class='sortUp'>▲</a>
                            <a href='{{ BadChoice\Thrust\ResourceFilters\Sort::link($field->field, 'asc')}}'  class='sortDown'>▼</a>
                        </div>
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
                        @if (! $field->shouldHideInIndex($row) && $field->shouldShowInIndex($row) && $resource->can($field->policyAction, $row))
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