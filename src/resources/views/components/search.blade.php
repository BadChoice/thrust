@if ($searchable)
    <div class="resourceSearch">
        <input id='searcher' placeholder="{{__('thrust::messages.search')}}" autofocus class="" value="{{request('search')}}">
        <div class="resourceSearch-icon" style=""> @icon(search)</div>
    </div>
@endif