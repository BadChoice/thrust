<?php
$actions = collect($resource->actions());
?>

@if (count($actions) > 0)
    @if ($actions->where('main', false)->count() > 0)
        <div x-data = "{isOpen : false}">
            <button class="secondary relative" x-on:click="isOpen = !isOpen"> <i id='actions-loading' class="fa fa-circle-o-notch fa-spin fa-fw" style="display:none"></i> {{ __("thrust::messages.actions") }} @icon(caret-down) </button>
            <div class="thrust-actions-dropdown absolute" x-show="isOpen" x-transition x-cloak x-on:click.away = "isOpen = false">
                @foreach($actions->where('main', false) as $action)
                    <div class="">
                        @if (count($action->fields()) == 0)
                            <a class='pointer' onclick='runAction("{{ $action->getClassForJs() }}", "{{$action->needsConfirmation}}", "{{$action->needsSelection}}", "{{$action->getConfirmationMessage()}}")'>
                                {!! $action->getIcon() !!} {!! $action->getTitle() !!}
                            </a>
                        @else
                            <a class='actionPopup' href="{{route('thrust.actions.create',[$resourceName])}}?action={{get_class($action)}}">
                                {!! $action->getIcon() !!} {!! $action->getTitle() !!}
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @foreach( $actions->where('main', true) as $action)
        <button class="secondary" onclick='runAction("{{ $action->getClassForJs() }}", "{{$action->needsConfirmation}}", "{{$action->needsSelection}}", "{{$action->getConfirmationMessage()}}")'> {!! icon($action->icon) !!} </button>
    @endforeach
@endif