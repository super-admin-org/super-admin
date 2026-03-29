<div class="grid-dropdown-actions relative inline-block">
    <a href="#" class="inline-flex items-center justify-center w-7 h-7 rounded hover:bg-gray-100/60 text-gray-400 transition-colors" data-sa-toggle="dropdown" data-sa-target="#grid-actions-{{ uniqid() }}">
        <i class="icon-ellipsis-v text-sm"></i>
    </a>
    <ul class="grid-actions-menu absolute right-0 mt-1 w-40 bg-white/90 backdrop-blur-md border border-gray-200/50 rounded-lg shadow-glass overflow-hidden z-50 hidden">

        @foreach($default as $action)
            <li class="hover:bg-gray-50/50">{!! $action->render() !!}</li>
        @endforeach

        @if(!empty($custom))

            @if(!empty($default))
            <li><hr class="border-gray-200/50 my-1"></li>
            @endif

            @foreach($custom as $action)
                <li class="hover:bg-gray-50/50">{!! $action->render() !!}</li>
            @endforeach
        @endif
    </ul>
</div>

@yield('child')
