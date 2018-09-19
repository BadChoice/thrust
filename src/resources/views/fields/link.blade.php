<a href="{{$url}}" class="{{$class}}">
    @if($icon)
        <i class="fa fa-{{$icon}}" style="color:black; font-size:15px"></i>
    @else
        {{$value}}
    @endif
</a>