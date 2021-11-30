@component('thrust::components.formField' , ["field" => $field, "title" => $title, "description" => $description, "inline" => $inline])
    <input type={{$type}} id="{{$field}}" value="{{$value}}" name="{{$field}}" placeholder="{{$title}}"
            {{$attributes}} {!! $validationRules !!} @if($inline) style="width:auto" @endif>
@endcomponent