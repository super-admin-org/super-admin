<div class="flex items-center gap-1">
    <a class="glass-btn-primary text-sm btn-filter {{ $btn_class }} {{ $expand ? '' : 'collapsed' }}"
       title="{{ trans('admin.filter') }}"
       data-sa-toggle="collapse"
       data-sa-target="#{{ $filter_id }}">
        <i class="icon-filter"></i>
        <span class="hidden sm:inline ml-1">{{ trans('admin.filter') }}</span>
        <i class="icon-angle-down collapse-icon transition-transform ml-1 text-xs"></i>
    </a>

    @if($scopes->isNotEmpty())
    <div class="relative">
        <button type="button" class="glass-btn-primary text-sm" data-sa-toggle="dropdown" data-sa-target="#scopes-dropdown">
            <span>{{ $label }}</span>
            <svg class="w-3 h-3 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <ul id="scopes-dropdown" class="absolute z-50 mt-1 bg-white/90 backdrop-blur-md border border-gray-200/50 rounded-lg shadow-glass overflow-hidden hidden min-w-36">
            @foreach($scopes as $scope)
                {!! $scope->render() !!}
            @endforeach
            <li><hr class="border-gray-200/50 my-1"></li>
            <li><a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50/50" href="{{ $cancel }}">{{ trans('admin.cancel') }}</a></li>
        </ul>
    </div>
    @endif
</div>
