<td class="action"><input class='actionCheckbox' type="checkbox" name="selected[{{$object->id}}]" meta:id="{{$object->id}}"></td>
    @if ($sortable)
        <td class="sort action hide-mobile"></td>
    @endif
<form action="{{route('thrust.update', [$resourceName, $object->id] )}}" id='thrust-form-{{$object->id}}' method="POST">
    @foreach($fields as $field)
        <td class="{{$field->rowClass}}">
            {!! $field->displayInEdit($object, true) !!}
        </td>
    @endforeach
    <td colspan="2">
        {{ method_field('PUT') }}
        {{ csrf_field() }}
        <input type="hidden" name="inline" value="1">
        <a class="button secondary" onclick="submitInlineForm({{$object->id}})"> {{ __("thrust::messages.save") }} </a>
    </td>
</form>
