@include('thrust::components.paginator', ["data" => $rows])
@if (count($rows) > 0)
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
            <th class="action text-right" colspan="2" >
                @include('thrust::components.tableDensity')
            </th>
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
                        @if (! $field->shouldHide($row) && $field->shouldShow($row))
                            {!! $field->displayInIndex($row) !!}
                        @endif
                    </td>
                @endforeach

                @if ($resource->canEdit($row))
                    <td class="action"> <a class='showPopup edit thrust-edit' href="{{route('thrust.edit', [$resource->name(), $row->id]) }}"></a> </td>
                @else
                    <td></td>
                @endif

                @if ($resource->canDelete($row))
                    <td class="action"> <a class="delete-resource thrust-delete" data-delete="confirm resource" href="{{route('thrust.delete', [$resource->name(), $row->id])}}"></a></td>
                @else
                    <td></td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
    @include('thrust::components.paginator',["data" => $rows])
@else
    @include('thrust::components.noData')
@endif