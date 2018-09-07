@paginator($rows)
<table class="list">
    <thead>
        @foreach($fields as $field)
            <th>
                <div class='flexContainer'>{{ $field->getTitle() }}
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

    @foreach ($rows as $row)
        <tr>
            @foreach($fields as $field)
                <td> {!! $field->displayInIndex($row) !!}</td>
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
</table>
@paginator($rows)
