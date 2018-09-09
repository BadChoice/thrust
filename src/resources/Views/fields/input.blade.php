<div class="label">{{ $title }}</div>
<div class="field">
    <input type={{$type}}
            id="{{$field}}" value="{{$value}}" name="{{$field}}"
            placeholder="{{$title}}"
            {{$attributes}} {!! $validationRules !!}>
    @if (isset($description))
        <p>{!! $description !!}</p>
    @endif
</div>