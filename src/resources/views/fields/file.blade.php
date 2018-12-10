@component('thrust::components.formField' , ["field" => $field, "title" => $title, "description" => $description, "inline" => $inline])
    @if($withLink)
    <a href="{{route('thrust.file.edit', [$resourceName, $id, $field]) }}" class="showPopup">
    @endif
        @if ($path && $exists)
            <span>{{ basename($path) }} @icon(pencil)</span>
        @else
            <span class="button secondary"> @icon(file) </span>
        @endif
    @if($withLink)
    </a>
    @endif
    @if ($exists && $path)
        <a href="{{ url($path) }}" style="margin-left: 10px">@icon(download)</a>
    @endif
@endcomponent
