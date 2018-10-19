<select name="{{ $filter->class() }}" title="{{$filter->title()}}">
    <option value="--">--</option>
    @foreach ($filter->options() as $key => $optionValue)
        <option value="{{$value}}" @if($value == $optionValue) selected @endif>{{$key}} </option>
    @endforeach
</select>