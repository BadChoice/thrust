<h2> {{$title}} </h2>
<table>
    @foreach ($children as $row)
        <tr>
            <td> {{ $row->{$relationshipDisplayName} }}</td>
            <td class="action"> <a class="delete-resource" data-delete="confirm resource" href="{{route('thrust.belongsToMany.delete', [$resourceName, $object->id, $belongsToManyField->field, $row->id])}}"></a></td>
        </tr>
    @endforeach
</table>

<div>
    <form action="{{route('thrust.belongsToMany.store', [$resourceName, $object->id, $belongsToManyField->field]) }}" method="POST">
        {{ csrf_field() }}
        <select name="id">
            @foreach($belongsToManyField->getOptions($object) as $possible)
                <option value='{{$possible->id}}'> {{ $possible->name }} </option>
            @endforeach
        </select>
        <button class="secondary">{{__('thrust::messages.add') }}</button>
    </form>
</div>

<script>
    addListeners();
</script>