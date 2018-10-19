@if ($withLink)
    <a href="{{route('thrust.belongsToMany', [$resourceName, $id , $relationship])}}" class="showPopup">
        @if($icon)
            <i class="fa fa-{{$icon}}" style="color:black; font-size:15px"></i>
        @elseif( strlen($value) == 0)
            --
        @else
            {{ $value }}
        @endif
    </a>
@else
    {{ $value }}
@endif