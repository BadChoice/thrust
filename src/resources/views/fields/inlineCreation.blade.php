<a class="button" onclick="create()"><i class="fa fa-plus" aria-hidden="true"></i></a>
<div class="thrust-inline-creation hidden" id="inline-creation-{{$field}}"></div>
@push('edit-scripts')
    <script>
        function create() {
            $("#inline-creation-{{$field}}").show('fast')
            $("#inline-creation-{{$field}}").load('/thrust/pullerProducts/create', function(){
                $('.thrust-inline-creation form').on('submit', function(e){
                    e.preventDefault()
                    var form = $(this)
                    var url = form.attr('action')
                    $.ajax({type: "POST", url: url,
                        data: form.serialize(), // serializes the form's elements.
                        success: function(data) {
                            alert(data); // show response from the php script.
                        }
                    });
                })
            })
        }
    </script>
@endpush