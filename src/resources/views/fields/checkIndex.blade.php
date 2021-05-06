@if($withLinks)
    <a href="{{route('thrust.toggle', [$resourceName, $id, $field])}}">
@endif
@if ($value)
    <i class="fa @if($asSwitch) fa-2x fa-toggle-on @else fa-check @endif green"></i>
@else
    <i class="fa @if($asSwitch) fa-2x fa-toggle-off @else fa-times red @endif " style="color:rgba(255,0,0,0.2)"></i>
@endif
@if($withLinks)
    </a>
@endif
