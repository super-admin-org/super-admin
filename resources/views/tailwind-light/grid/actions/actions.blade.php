<div class="__actions__div flex items-center gap-1 @if(!empty($showLabels))with-labels @endif">
    @foreach($default as $action)
        {!! $action->render() !!}
    @endforeach
    @if(!empty($custom))
        @if(!empty($default))
        <span class="w-px h-4 bg-gray-300 mx-1"></span>
        @endif
        @foreach($custom as $action)
            {!! $action->render() !!}
        @endforeach
    @endif
</div>
@yield('child')
