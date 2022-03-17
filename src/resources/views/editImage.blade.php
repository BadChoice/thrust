<div>
    @if ($fileField->displayPath($object))
        <img class='' src="{{ url($fileField->displayPath($object)) }}" style="">
    @endif
</div>

<div>
    <form action="{{ route('thrust.image.store', [$resourceName, $object->id, $fileField->field]) }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="file" name="image">
        <div class="mt-4">
            <button class="button-with-loading">{{ __("thrust::messages.save") }}</button>
        </div>
    </form>
</div>
<div class="ml-28 -mt-7">
    <form action="{{ route('thrust.image.delete', [$resourceName, $object->id, $fileField->field]) }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('delete')  }}
        <button class="secondary">@icon(trash) {{ __("thrust::messages.delete") }}</button>
    </form>
</div>
