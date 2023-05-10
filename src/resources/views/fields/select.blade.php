@component('thrust::components.formField', ["field" => $field, "title" => $title, "description" => $description ?? null, "inline" => $inline])
    <select id="{{$field}}" name="{{$field}}" @if($searchable) class="searchable" @endif {{$attributes ?? ""}}>
        @if (isset($hasCategories) && $hasCategories))
            @foreach($options as $category => $values)
                <optgroup label="{{$category}}">
                    @foreach($values as $key => $optionValue)
                        <option @if((! $key && $key === 0) || $key == $value) selected @endif value="{{$key}}">{{$optionValue}}</option>
                    @endforeach
                </optgroup>
            @endforeach
        @else
            @foreach($options as $key => $optionValue)
                <option @if((! $key && $key === 0) || $key == $value) selected @endif value="{{$key}}">{{$optionValue}}</option>
            @endforeach
        @endif
    </select>
    @if(isset($inlineCreation) && $inlineCreation)
        @include('thrust::fields.inlineCreation')
    @endif
@endcomponent