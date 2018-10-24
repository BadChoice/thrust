<div>
    @if ($fileField->displayPath($object) && $fileField->exists($object))
        @icon(file) <span class='br1'>{{ basename($fileField->displayPath($object)) }}</span>
    @endif
</div>

<div class="inline mt4">
    <form action="{{ route('thrust.file.store', [$resourceName, $object->id, $fileField->field]) }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="file" name="file">
        <br>
        <button>{{ __("thrust::messages.save") }}</button>
    </form>
</div>
<div class="inline" style="margin-left: -205px;">
    <form action="{{ route('thrust.file.delete', [$resourceName, $object->id, $fileField->field]) }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('delete')  }}
        <button class="secondary">@icon(trash) {{ __("thrust::messages.delete") }}</button>
    </form>
</div>
