<div>
    @if ($fileField->displayPath($object))
        <img class='' src="{{ url($fileField->displayPath($object)) }}" style="">
    @endif
</div>

<div class="inline">
    <form action="{{ route('thrust.image.store', [$resourceName, $object->id, $fileField->field]) }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="file" name="image" accept="image/png, image/gif, image/jpeg">
        <div>
            <button class="button-with-loading">{{ __("thrust::messages.save") }}</button>
        </div>
    </form>
</div>
<div class="inline ml-28">
    <form action="{{ route('thrust.image.delete', [$resourceName, $object->id, $fileField->field]) }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('delete')  }}
        <button class="secondary">@icon(trash) {{ __("thrust::messages.delete") }}</button>
    </form>
</div>
