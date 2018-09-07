<div class="label">{{ $title }}</div>
<div class="field">
    <select id="{{$field}}" name="{{$field}}">
        @foreach($options as $key => $optionValue)
            <option @if($key == $value) selected @endif value="{{$key}}">{{$optionValue}}</option>
        @endforeach
    </select>
</div>