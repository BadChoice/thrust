@if ($searchable)
    <div class="resourceSearch mb5">
        <input id='searcher' placeholder="{{__('thrust::messages.search')}}" autofocus class="shadow-outer-3" value="{{request('search')}}">
        <div style="position:absolute; float:left; margin-left:280px; margin-top:-22px;">
            @icon(search)
        </div>
    </div>
@endif