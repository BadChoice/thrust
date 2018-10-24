<script>
    $('input').on('change', function () {
        $('#thrust-save-and-continue').show();
    });

    function submitAjaxForm(id) {
        var formToSubmit = $("#" + id);
        $.ajax({
            url: formToSubmit.attr('action'),
            method: formToSubmit.attr('method'),
            data: formToSubmit.serialize(),
            dataType: 'JSON',
            success: function (data) {
                //loadPopup("/admin/catalog/products/" + data + "/edit");
                showMessage('done')
            }
        }).fail(function (result, textStatus, errorThrown) {
            showMessage(errorThrown)
        });
    }
</script>