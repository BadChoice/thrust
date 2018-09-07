@if ( method_exists($data, 'links') )
    {{  $data->appends(array_except(request()->query(),['page']))->links() }}
@endif