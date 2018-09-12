<a href="{{route('thrust.image.edit', [$resourceName, $id, $field]) }}" class="showPopup">
    @if ($path)
        <img src='{{ asset($path) }}' class='{{$classes}}' style='height:30px; width:30px; object-fit: cover;'>
    @elseif($gravatar)
        {!! $gravatar !!}
    @else
        +
    @endif
</a>