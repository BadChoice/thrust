<script>
    $("tr").on("dblclick", function(element){
        if ($(this).find("a.edit").length == 0) return;
        editInline($(this).attr('id').replace("sort_", ""));
    });

    function editInline(id){
        var url = "{{route('thrust.editInline', [$resourceName, 1])}}".replace("1", id);
        $('#sort_'+id).load(url, () => {
           $(`#sort_${id} input, #sort_${id} textarea, #sort_${id} select`).each((index, el)=>{ el.setAttribute('form', `thrust-form-${id}`)})
        });
    }

    function submitInlineForm(id){
        $('#sort_'+id).find("td input,td select").each(function() {
            $(this).attr("form", "thrust-form-" + id);
        });

        form = $("#thrust-form-"+id);
        if($('#thrust-form-'+id, '{{config('thrust.popupId', '#popup')}}').length == 1) {
            var data = {}
            for(value of new FormData(form[0])) {
                data[value[0]] = value[1]
            }
            callAjax(form.attr('action'), data)
            return
        }
        form.submit();
    }
</script>