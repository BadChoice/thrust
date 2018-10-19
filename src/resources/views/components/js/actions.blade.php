<script>
    $(".actionPopup").on('click',function(e) {
        e.preventDefault();
        var selected = getSelectedRowsIds();
        if (selected.length == 0){
            return alert("{{ __("thrust::messages.noRowsSelected") }}");
        }

        $(this).attr('href', $(this).attr('href') + "&ids=" + selected);
        showPopup($(this).attr('href'));
    });


    function runAction(actionClass, needsConfirmation, confirmationMessage){
        var selected = getSelectedRowsIds();
        console.log(actionClass, selected, needsConfirmation);

        if (selected.length == 0){
            return alert("{{ __("thrust::messages.noRowsSelected") }}");
        }

        if (!needsConfirmation || confirm(confirmationMessage)){
            doAction(actionClass, selected)
        }
    }

    function doAction(actionClass, selected){
        $.post("{{ route('thrust.actions.perform', [$resourceName]) }}", {
            "_token": "{{ csrf_token() }}",
            "action" : actionClass,
            "ids" : selected
        }).done(function(data){
            showMessage(data["message"]);
            location.reload();
        }).fail(function(){
            showMessage("Something went wrong");
        });
    }

    function getSelectedRowsIds(){
        return $("input[name^=selected]:checked").map(function() {
            return $(this).attr("meta:id");
        }).toArray();
    }

    function toggleSelectAll(checkbox){
        $("input[name^=selected]").prop('checked', checkbox.checked);
    }
</script>