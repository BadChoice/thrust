<div class="label">{{__(config('thrust.translationsPrefix').$title)}}</div>
<div class="field">
    <input type={{$type}}
            id="{{$field}}" value="{{$value}}" name="{{$field}}"
            {{$attributes}} {!! $validationRules !!}>
</div>