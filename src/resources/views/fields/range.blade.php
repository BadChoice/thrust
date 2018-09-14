@component('thrust::components.formField' , ["field" => $field, "title" => $title, "description" => $description, "inline" => $inline])
    <span id="rangeInput_{{$field}}">{{ $value }}</span> {{ $unit }}<br>
    <input id="{{$field}}" name='{{$field}}' type="range" {{$attributes}} value="{{$value}}" onchange="$('#rangeInput_{{$field}}').text(this.value);" /><br>
@endcomponent