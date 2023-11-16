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
