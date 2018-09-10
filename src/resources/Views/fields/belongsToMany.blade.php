@if ($withLink)
    <a href="{{route('thrust.belongsToMany', [$resourceName, $id , $relationship])}}" class="showPopup">
        @if( strlen($value) == 0)
            --
        @else
            {{ $value }}
        @endif
    </a>
@else
    {{ $value }}
@endif