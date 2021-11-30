@component('thrust::components.formField', ["field" => $field, "title" => $title, "description" => $description ?? null, "inline" => $inline])
    <input hidden name="{{$field}}" value="">
    <select id="{{$field}}" name="{{$field}}[]" @if($searchable) class="searchable" @endif multiple>
        @foreach($options as $key => $optionValue)
            <option @if($value && in_array($key, (array)$value)) selected @endif value="{{$key}}">{{$optionValue}}</option>
        @endforeach
    </select>
    @if($clearable)
        <input type="button" id="{{$field}}_clear_selection" name="clear_selection" value="{{__('thrust::messages.clearSelection')}}" class="button secondary" style="width:auto">
        <script>
        $('#{{$field}}_clear_selection').click(function() {
            $('#{{$field}} option').prop('selected', false);
            $('#{{$field}}').trigger('change');
        });
        </script>
    @endif
@endcomponent