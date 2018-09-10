<h2> {{$title}} </h2>
<table>
    @foreach ($children as $row)
        <tr>
            <td> {{ $row->{$relationshipDisplayName} }}</td>
            @foreach($belongsToManyField->pivotFields as $field)
                <td>{!! $field->displayInIndex($row)  !!}</td>
            @endforeach

            <td class="action"> <a class="delete-resource" data-delete="confirm resource" href="{{route('thrust.belongsToMany.delete', [$resourceName, $object->id, $belongsToManyField->field, $row->id])}}"></a></td>

        </tr>
    @endforeach
</table>

<div class="mt4">
    <form action="{{route('thrust.belongsToMany.store', [$resourceName, $object->id, $belongsToManyField->field]) }}" method="POST">
        {{ csrf_field() }}
        <select id="id" name="id" @if($belongsToManyField->searchable) class="searchable" @endif >
            @if (!$ajaxSearch)
                @foreach($belongsToManyField->getOptions($object) as $possible)
                    <option value='{{$possible->id}}'> {{ $possible->name }} </option>
                @endforeach
            @endif
        </select>
        @foreach($belongsToManyField->pivotFields as $field)
            {!! $field->displayInEdit(null)  !!}
        @endforeach
        <button class="secondary">{{__('thrust::messages.add') }} </button>
    </form>
</div>

<script>
    addListeners();
    // $('#popup > select > .searchable').select2({ width: '325', dropdownAutoWidth : true });
    @if ($searchable && !$ajaxSearch)
        $('.searchable').select2({
            width: '300px',
            dropdownAutoWidth : true,
            dropdownParent: $('#popup')
        });
    @endif

    @if ($ajaxSearch)
        new RVAjaxSelect2('{{ route('thrust.relationship.search', [$resourceName, $object->id, $belongsToManyField->field]) }}?allowNull=0&allowDuplicates={{$allowDuplicates}}',{
            dropdownParent: $('#popup'),
        }).show('#id');
    @endif
</script>