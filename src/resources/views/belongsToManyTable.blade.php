<table class="list striped">
    <thead>
    @if (! $belongsToManyField->hideName)
        @if ($sortable)
            <th></th>
        @endif
        <th>
            {{ trans_choice(config('thrust.translationsPrefix') . str_singular($belongsToManyField->field), 1) }}
        </th>
    @endif
    @foreach($belongsToManyField->objectFields as $field)
        <th> {{$field->getTitle()}}</th>
    @endforeach
    @foreach($belongsToManyField->pivotFields as $field)
        <th> {{$field->getTitle()}}</th>
    @endforeach
    <th></th>
    </thead>
    <tbody class="@if ($sortable) sortableChild @endif">
    @foreach ($children as $row)
        <tr id="sort_{{$row->pivot->id}}">
            @if ($sortable)
                <td class="sort action hide-mobile"></td>
            @endif
            @if (! $belongsToManyField->hideName)
                <td>
                    {{ $row->{$relationshipDisplayName} }}
                </td>
            @endif
            @foreach($belongsToManyField->objectFields as $field)
                <td>{!! $field->displayInIndex($row)  !!}</td>
            @endforeach
            @foreach($belongsToManyField->pivotFields as $field)
                <td>{!! $field->displayInIndex($row->pivot)  !!}</td>
            @endforeach
            <td class="action"> <a class="delete-resource" data-delete="confirm resource" href="{{route('thrust.belongsToMany.delete', [$resourceName, $object->id, $belongsToManyField->field, $row->pivot->id])}}"></a></td>
        </tr>
    @endforeach
    </tbody>
</table>
@paginator($children)
