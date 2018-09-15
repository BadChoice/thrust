@component('thrust::components.formField', ["field" => $field, "title" => $title, "description" => $description ?? null, "inline" => $inline])
    <select id="{{$field}}" name="{{$field}}" @if($searchable) class="searchable" @endif>
        @foreach($options as $key => $optionValue)
            <option @if($key === $value) selected @endif value="{{$key}}">{{$optionValue}}</option>
        @endforeach
    </select>
@endcomponent