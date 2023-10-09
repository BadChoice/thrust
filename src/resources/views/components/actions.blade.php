@if (count($actions) > 0)
    @if ($actions->where('main', false)->count() > 0)
        <div x-data = "{isOpen : false}">
            <button class="secondary relative" x-on:click="isOpen = !isOpen"> <i id='actions-loading' class="fa fa-circle-o-notch fa-spin fa-fw" style="display:none"></i> {{ __("thrust::messages.actions") }} @icon(caret-down) </button>
            <div id="thrust-resource-actions" class="thrust-actions-dropdown absolute" x-show="isOpen" x-transition x-cloak x-on:click.away = "isOpen = false">
                @include('thrust::components.actionsIndex')
            </div>
        </div>
    @endif

    @foreach( $actions->where('main', true) as $action)
        <button class="secondary" onclick='runAction("{{ $action->getClassForJs() }}", "{{$action->needsConfirmation}}", "{{$action->needsSelection}}", "{{$action->getConfirmationMessage()}}")'> {!! icon($action->icon) !!} </button>
    @endforeach
@endif
