@component('thrust::components.formField', ["field" => $field, "title" => $title, "description" => $description ?? null, "inline" => $inline])
    <input hidden name="{{$field}}" value="">
    <select id="{{$field}}" name="{{$field}}[]" @if($searchable) class="searchable" @endif multiple>
        @foreach($options as $key => $optionValue)
            <option @if($value && in_array($key, (array)$value)) selected @endif value="{{$key}}">{{$optionValue}}</option>
        @endforeach
    </select>
@endcomponent