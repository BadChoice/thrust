@include('thrust::components.paginator',["data" => $rows])
@if (count($rows) > 0)
    <table class="list">
        <thead>
            @if ($sortable)
                <th class="hide-mobile">  </th>
            @endif
            @foreach($fields as $field)
                <th class="{{$field->rowClass}}">
                    <div class='sortableHeader'>{{ $field->getTitle() }}
                    @if ($field->sortable && !request('search'))
                        <div class='sortArrows'>
                            <a href='{{ BadChoice\Thrust\ResourceFilters\Sort::link($field->field, 'desc')}}' class='sortUp'>▲</a>
                            <a href='{{ BadChoice\Thrust\ResourceFilters\Sort::link($field->field, 'asc')}}'  class='sortDown'>▼</a>
                        </div>
                    @endif
                    </div>
                </th>
            @endforeach
            <th class="action" colspan="2"></th>
        </thead>

        <tbody class="@if($sortable) sortable @endif">
        @foreach ($rows as $row)
            <tr id="sort_{{$row->id}}">
                @if ($sortable)
                    <td class="sort action hide-mobile"></td>
                @endif
                @foreach($fields as $field)
                    <td class="{{$field->rowClass}}">
                        @if (! $field->shouldHide($row))
                            {!! $field->displayInIndex($row) !!}
                        @endif
                    </td>
                @endforeach

                @if ($resource->canEdit($row))
                    <td class="action"> <a class='showPopup edit' href="{{route('thrust.edit', [$resource->name(), $row->id]) }}"> </a> </td>
                @else
                    <td></td>
                @endif

                @if ($resource->canEdit($row))
                    <td class="action"> <a class="delete-resource" data-delete="confirm resource" href="{{route('thrust.delete', [$resource->name(), $row->id])}}"></a></td>
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
