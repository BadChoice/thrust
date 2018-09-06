<div class="label">{{ $title }}</div>
<div class="field">
    <input type={{$type}}
            id="{{$field}}" value="{{$value}}" name="{{$field}}"
            {{$attributes}} {!! $validationRules !!}>
</div>