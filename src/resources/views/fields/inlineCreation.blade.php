<a class="button" onclick="thrustCreateInline{{$field}}()"><i class="fa fa-plus" aria-hidden="true"></i></a>
<div class="thrust-inline-creation hidden" id="inline-creation-{{$field}}"></div>
@push('edit-scripts')
    <script>
        function thrustCreateInline{{$field}}() {
            $("#inline-creation-{{$field}}").show('fast')
            $("#inline-creation-{{$field}}").load('/thrust/{{$inlineCreationData['relationResource']}}/create', function(){
                $('#inline-creation-{{$field}} form').on('submit', function(e){
                    e.preventDefault()
                    var form = $(this)
                    var url  = form.attr('action')
                    $.ajax({type: "POST", url: url,
                        data: form.serialize(), // serializes the form's elements.
                        success: function(data) {
                            alert(data); // show response from the php script.
                            $('#inline-creation-{{$field}}').hide('fast');
                            const id = data['id'];
                            const value = data['{{$inlineCreationData['relationDisplayField']}}']
                            $('#{{$field}}').append("<option value='" + id + "' selected>" + value + "<option>")
                        }
                    });
                })
            })
        }
    </script>
@endpush