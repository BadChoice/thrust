
@if ($sortable)
    <td class="sort action hide-mobile"></td>
@endif
<form action="{{route('thrust.update', [$resourceName, $object->id] )}}" id='thrust-form-{{$object->id}}' method="POST" onkeydown="console.log(event.key)">
    @if (! $belongsToManyField->hideName)
        <td></td>
    @endif
    @foreach($belongsToManyField->objectFields as $field)
        <td></td>
    @endforeach
    @foreach($fields as $field)
        <td class="{{$field->rowClass}}">
            @if ($field->showInEdit)
                {!! $field->displayInEdit($object, true) !!}
            @endif
        </td>
    @endforeach
    <td colspan="2">
        {{ method_field('PUT') }}
        {{ csrf_field() }}
        <input type="hidden" name="inline" value="1">
        <button class="button secondary" onclick="submitInlineForm({{$object->id}}); event.preventDefault();" form="thrust-form-{{ $object->id }}"> {{ __("thrust::messages.save") }} </button>
    </td>
</form>