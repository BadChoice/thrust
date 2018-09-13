@if (count($resource->actions()) > 0)
    <div class="pt4 pb1 text-right">
        <div class="dropdown inline">
            <button class="secondary"> {{ __("thrust::messages.actions") }} @icon(caret-down) </button>
        </div>
        <ul class="dropdown-container" style="right:100px">
            @foreach(collect($resource->actions())->where('main', false) as $action)
                <li class="text-left"><a class='pointer' onclick='runAction("{{ $action->getClassForJs() }}", {{$action->needsConfirmation}}, "{{$action->confirmationMessage}}")'>{!! $action->getTitle() !!}</a></li>
            @endforeach
        </ul>
        @foreach( collect($resource->actions())->where('main', true) as $action)
            <button class="secondary" onclick='runAction("{{ $action->getClassForJs() }}", {{$action->needsConfirmation}}, "{{$action->confirmationMessage}}")'> {!! icon($action->icon) !!} </button>
        @endforeach
    </div>
@endif