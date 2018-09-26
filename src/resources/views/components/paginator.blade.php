@if ( method_exists($data, 'links')  && $data->lastPage() != 1)
    <div class="mt4">
        {{  $data->appends(array_except(request()->query(),['page']))->links() }}
    </div>
@endif