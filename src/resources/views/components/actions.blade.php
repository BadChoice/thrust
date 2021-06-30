<?php
$actions = collect($resource->actions());
?>

@if (count($actions) > 0)
    @if ($actions->where('main', false)->count() > 0)
        <div class="dropdown inline">
            <button class="secondary"> <i id='actions-loading' class="fa fa-circle-o-notch fa-spin fa-fw" style="display:none"></i> {{ __("thrust::messages.actions") }} @icon(caret-down) </button>
        </div>

        <ul class="dropdown-container" style="right:10px">
            @foreach($actions->where('main', false) as $action)
                <li class="text-left">
                    @if (count($action->fields()) == 0)
                        <a class='pointer' onclick='runAction("{{ $action->getClassForJs() }}", "{{$action->needsConfirmation}}","{{$action->needsSelection}}", "{{$action->confirmationMessage}}")'>
                            {!! $action->getIcon() !!} {!! $action->getTitle() !!}
                        </a>
                    @else
                        <a class='actionPopup' href="{{route('thrust.actions.create',[$resourceName])}}?action={{get_class($action)}}">
                            {!! $action->getIcon() !!} {!! $action->getTitle() !!}
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif

    @foreach( $actions->where('main', true) as $action)
        <button class="secondary" onclick='runAction("{{ $action->getClassForJs() }}", "{{$action->needsConfirmation}}","{{$action->needsSelection}}", "{{$action->confirmationMessage}}")'> {!! icon($action->icon) !!} </button>
    @endforeach
@endif