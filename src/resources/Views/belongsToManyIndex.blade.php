<h2> {{$title}} </h2>
<table>
    @foreach($children as $child)
        <tr>
            <td> {{ $child->{$relationshipDisplayName} }}</td>
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