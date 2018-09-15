<script>
    $("tr").on("dblclick", function(element){
        editInline($(this).attr('id').replace("sort_", ""));
    });

    function editInline(id){
        var url = "{{route('thrust.editInline', [$resourceName, 1])}}".replace("1", id);
        $('#sort_'+id).load(url);
    }

    function submitInlineForm(id){
        $('#sort_'+id).find("td input,td select").each(function() {
            $(this).attr("form", "thrust-form-" + id);
        });
        $("#thrust-form-"+id).submit();
    }
</script>