@component('thrust::components.formField' , ["field" => $field, "title" => $title, "description" => $description, "inline" => $inline])
    @if($withLink)
    <a href="{{route('thrust.image.edit', [$resourceName, $id, $field]) }}" class="showPopup">
    @endif
        @if ($path && $exists)
            <img src='{{ asset($path) }}' class='{{$classes}}' style='{{$style}}'>
        @elseif($path)
            <span class="button secondary"> @icon(warning) </span>
        @elseif($gravatar)
            {!! $gravatar !!}
        @else
            <span class="button secondary"> @icon(photo) </span>
        @endif
    @if($withLink)
    </a>
    @endif
@endcomponent
