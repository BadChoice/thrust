@if ($searchable)
    <div class="mt4">
        <input id='searcher' placeholder="{{__('thrust::messages.search')}}" autofocus class="shadow-outer-3">
        <div style="position:absolute; float:left; margin-left:280px; margin-top:-22px;">
            @icon(search)
        </div>
    </div>
@endif