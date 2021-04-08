<script>
    $('#searcher').searcher('{{$searchUrl ?? "/thrust/{$resourceName}/search/"}}', {
        'onFound' : function(){
            addListeners();
        },
        'minChars' : {{ config('thrust.minSearchChars') }}
    });
</script>