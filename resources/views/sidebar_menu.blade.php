<li class="{{ (request()->is($route.'*')) ? 'active' : '' }}">
    <a href="{{route($route.'.index')}}">{{$routeText}}</a>
</li>
