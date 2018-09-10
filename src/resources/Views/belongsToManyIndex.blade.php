<h2> {{$title}} </h2>
<table>
    @foreach ($children as $row)
        <tr>
            <td> {{ $row->{$relationshipDisplayName} }}</td>
            <td class="action"> <a class="delete-resource" data-delete="confirm resource" href="{{route('thrust.belongsToMany.delete', [$resourceName, $object->id, $belongsToManyField->field, $row->id])}}"></a></td>
        </tr>
    @endforeach
</table>

<div class="mt4">
    <form action="{{route('thrust.belongsToMany.store', [$resourceName, $object->id, $belongsToManyField->field]) }}" method="POST">
        {{ csrf_field() }}
        <select name="id" @if($belongsToManyField->searchable) class="searchable" @endif >
            @foreach($belongsToManyField->getOptions($object) as $possible)
                <option value='{{$possible->id}}'> {{ $possible->name }} </option>
            @endforeach
        </select>
        <button class="secondary">{{__('thrust::messages.add') }}</button>
    </form>
</div>

<script>
    addListeners();
    // $('#popup > select > .searchable').select2({ width: '325', dropdownAutoWidth : true });
    $('.searchable').select2({
        width: '300px',
        dropdownAutoWidth : true,
        dropdownParent: $('#popup')
    });
</script>