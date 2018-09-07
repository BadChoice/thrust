<div class="label">{{ $title }}</div>
<div class="field">
    <input type="hidden" value="0" name="{{$field}}">
    <input type="checkbox" id="{{$field}}" @if($value) checked @endif value="1" name="{{$field}}">
</div>