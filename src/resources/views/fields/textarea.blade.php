<div class="label">{{ $title }}</div>
<div class="field">
    <textarea id="{{$field}}" name="{{$field}}" placeholder="{{$title}}" {{$attributes}} {!! $validationRules !!} >{{$value}}</textarea>
</div>