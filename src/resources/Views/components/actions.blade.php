<?php
$actions = collect($resource->actions());
?>

@if (count($actions) > 0)
        @if ($actions->where('main', false)->count() > 0)
            <div class="dropdown inline">
                <button class="secondary"> {{ __("thrust::messages.actions") }} @icon(caret-down) </button>
            </div>

            <ul class="dropdown-container" style="right:10px">
                @foreach($actions->where('main', false) as $action)
                    <li class="text-left">
                        @if (count($action->fields()) == 0)
                            <a class='pointer' onclick='runAction("{{ $action->getClassForJs() }}", {{$action->needsConfirmation}}, "{{$action->confirmationMessage}}")'>{!! $action->getTitle() !!}</a>
                        @else
                            <a class='actionPopup' href="{{route('thrust.actions.create',[$resourceName])}}?action={{get_class($action)}}">{!! $action->getTitle() !!}</a>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif

        @foreach( $actions->where('main', true) as $action)
            <button class="secondary" onclick='runAction("{{ $action->getClassForJs() }}", {{$action->needsConfirmation}}, "{{$action->confirmationMessage}}")'> {!! icon($action->icon) !!} </button>
        @endforeach
@endif