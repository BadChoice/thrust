@if ($withLink)
    @if($link)
        <a href="{{ url($link) }}" class="">
    @else
        <a href="{{route('thrust.hasMany', [$resourceName, $id , $relationship])}}" class="">
    @endif
        @if( strlen($value) == 0)
            --
        @else
            {{ $value }}
        @endif
    </a>
@else
    {{ $value }}
@endif