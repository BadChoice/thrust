@component('thrust::components.formField' , ["field" => $field, "title" => $title, "description" => $description, "inline" => $inline])
    <a href="{{route('thrust.image.edit', [$resourceName, $id, $field]) }}" class="showPopup">
        @if ($path)
            <img src='{{ asset($path) }}' class='{{$classes}}' style='{{$style}}'>
        @elseif($gravatar)
            {!! $gravatar !!}
        @else
            <span class="button secondary"> @icon(photo) </span>
        @endif
    </a>
@endcomponent
