@component('thrust::components.formField', ["field" => $field, "title" => $title, "description" => $description ?? null])
    <textarea id="{{$field}}" name="{{$field}}" placeholder="{{$title}}" {{$attributes}} {!! $validationRules !!} @if($inline) form="{{ $formId }}" @endif>{{$value}}</textarea>
@endcomponent