@component('thrust::components.formField' , ["field" => $field, "title" => $title, "description" => $description, "inline" => $inline])
    <input type="color" id="colorpicker" value="{{$value}}" name="{{$field}}" placeholder="{{$title}}">
    <input type="text" id="{{$field}}" value="{{$value}}" name="{{$field}}"placeholder="{{$title}}">

    @push('edit-scripts')
        <script>
            $('#colorpicker').on('change', function() {
                $('#color').val(this.value); }
            );
        </script>
    @endpush

@endcomponent