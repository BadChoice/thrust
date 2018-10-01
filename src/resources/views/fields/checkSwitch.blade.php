@component('thrust::components.formField', ["field" => $field, "title" => $title, "description" => $description ?? null, 'inline' => $inline])
    <input id="{{$field}}Switch" type="hidden" value="{{$value?$value:'0'}}" name="{{$field}}">
    <i id="{{$field}}" class="fa @if($value) fa-toggle-on green @else fa-toggle-off red o20 @endif" style="font-size:24px"></i>

    @push('edit-scripts')
        <script>
            $("#{{$field}}").on('click', function(){
                if ($(this).hasClass('fa-toggle-on')) {
                    $(this).removeClass('fa-toggle-on green').addClass('fa-toggle-off red o20');
                    $('#{{$field}}Switch').val('0');
                }
                else{
                    $(this).removeClass('fa-toggle-off red o20').addClass('fa-toggle-on green');
                    $('#{{$field}}Switch').val('1');
                }
            });
        </script>
    @endpush

@endcomponent