@if ( method_exists($data, 'links')  && $data->lastPage() != 1)
    <div class="mt4">
        {{  $data->appends(array_except(request()->query(),['page']))->links() }}
    </div>
    @if(isset($popupLinks))
        <script>
            $(".page-link").on('click', function(e){
                e.preventDefault();
                loadPopup($(this).attr('href'));
            })
        </script>
    @endif
@endif