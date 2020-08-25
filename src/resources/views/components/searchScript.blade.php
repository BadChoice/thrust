<script>
    $('#searcher').searcher('/thrust/{{$resourceName}}/search/', {
        'onFound' : function(){
            addListeners();
        },
        'minChars' : {{ config('thrust.minSearchChars') }}
    });
</script>