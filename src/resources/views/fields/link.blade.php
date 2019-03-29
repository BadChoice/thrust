<a href="{{$url}}" class="{{$class}}" {{$attributes ?? ''}}>
    @if(isset($icon) && $icon)
        <i class="fa fa-{{$icon}}" style="color:black; font-size:15px"></i>
    @else
        {{$value}}
    @endif
</a>