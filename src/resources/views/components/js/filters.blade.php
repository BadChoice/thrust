<script>
    $('#filtersForm').submit(function(e) {
        e.preventDefault();
        var serialized = $(this).serialize();
        var params = [
            "filters=" + btoa(serialized)
        ];
        window.location.href = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + params.join('&');
    });
</script>