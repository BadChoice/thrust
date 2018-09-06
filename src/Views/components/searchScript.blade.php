<script>
    $('#searcher').searcher('/thrust/{{$resourceName}}/search/', {
        'onFound' : function(){
            addListeners();
        }
    });
</script>