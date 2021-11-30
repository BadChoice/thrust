@component('thrust::components.formField', ["field" => $field, "title" => $title, "description" => $description ?? null, 'inline' => $inline])
    <input type="hidden" value="0" name="{{$field}}">
    <input type="checkbox" id="{{$field}}" @if($value) checked @endif value="1" name="{{$field}}">
@endcomponent