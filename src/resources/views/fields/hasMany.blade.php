@if ($withLink)
    @if($link)
        <a href="{{ url($link) }}" class="">
    @else
        <a href="{{route('thrust.hasMany', [$resourceName, $id , $relationship])}}" class="">
    @endif
        @if($icon)
            <i class="fa fa-{{$icon}}" style="color:black; font-size:15px"></i> {{ $value }}
        @elseif( strlen($value) == 0)
            --
        @else
            {{ $value }}
        @endif
    </a>
@else
    {{ $value }}
@endif