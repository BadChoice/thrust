<table>
    <thead>
    @if (! $belongsToManyField->hideName)
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
    @foreach ($children as $row)
        <tr>
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

            <td class="action"> <a class="delete-resource" data-delete="confirm resource" href="{{route('thrust.belongsToMany.delete', [$resourceName, $object->id, $belongsToManyField->field, $row->id])}}"></a></td>

        </tr>
    @endforeach
</table>