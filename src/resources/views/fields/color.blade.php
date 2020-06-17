@component('thrust::components.formField' , ["field" => $field, "title" => $title, "description" => $description, "inline" => $inline])
    @php $id = str_replace("]","-",str_replace("[", "", $field))  @endphp
    <input type="color" id="colorpicker-{{$id}}" value="{{$value}}" name="{{$field}}" placeholder="{{$title}}">
    <input type="text" id="{{$id}}" value="{{$value}}" name="{{$field}}" placeholder="{{$title}}">

    @push('edit-scripts')
        <script>
            $('#colorpicker-{{$id}}').on('change', function() {
                $('#{{$id}}').val(this.value); }
            );
        </script>
    @endpush

@endcomponent