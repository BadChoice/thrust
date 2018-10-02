<script>
    /*$.extend($.expr[':'], {
        'containsi': function(elem, i, match, array) {
            return (elem.textContent || elem.innerText || '').toLowerCase()
                .indexOf((match[3] || "").toLowerCase()) >= 0;
        }
    });*/

    $('#searchSingle').on('keyup', function(e){
        var searchText = $(this).val();
        if (searchText.length == 0){
            $(".formPanel h4").show();
            $(".label").parent().show();
            $(".configForm .formPanel").css('border-bottom', '1px solid #f0f0f0');
        }
        else{
            $(".formPanel h4").hide();
            $(".label").parent().hide();
            $(".label").filter(function() {
                var reg = new RegExp(searchText, "i");
                return reg.test($(this).text());
            }).parent().show();
            $(".configForm .formPanel").css('border-bottom', 'none');
        }
    });
</script>