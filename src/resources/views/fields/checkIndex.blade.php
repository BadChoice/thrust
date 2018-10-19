@if($withLinks)
    <a href="{{route('thrust.toggle', [$resourceName, $id, $field])}}">
@endif
@if ($value)
    <i class="fa @if($asSwitch) fa-2x fa-toggle-on @else fa-check @endif green"></i>
@else
    <i class="fa @if($asSwitch) fa-2x fa-toggle-off o20 @else fa-times red @endif " style="color:red"></i>
@endif
@if($withLinks)
    </a>
@endif
