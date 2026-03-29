@if(Admin::user()->visible(\Illuminate\Support\Arr::get($item, 'roles', [])) && Admin::user()->can(\Illuminate\Support\Arr::get($item, 'permission')))
    @if(!isset($item['children']))
        {{-- Single menu item --}}
        <li>
            @if(url()->isValidUrl($item['uri']))
                <a href="{{ $item['uri'] }}" target="_blank" class="glass-menu-item {{ request()->is(trim(config('admin.route.prefix'), '/') . '/' . trim($item['uri'], '/') . '*') ? 'glass-menu-item-active' : '' }}">
            @else
                <a href="{{ admin_url($item['uri']) }}" class="glass-menu-item {{ request()->is(trim(config('admin.route.prefix'), '/') . '/' . trim($item['uri'], '/') . '*') ? 'glass-menu-item-active' : '' }}">
            @endif
                <i class="{{ $item['icon'] }} w-5 text-center text-base opacity-70"></i>
                @if (Lang::has($titleTranslation = 'admin.menu_titles.' . trim(str_replace(' ', '_', strtolower($item['title'])))))
                    <span class="sidebar-text">{{ __($titleTranslation) }}</span>
                @else
                    <span class="sidebar-text">{{ admin_trans($item['title']) }}</span>
                @endif
            </a>
        </li>
    @else
        {{-- Menu item with children --}}
        <li>
            <button data-sa-toggle="collapse" data-sa-target="#collapse-{{ $item['id'] }}" class="glass-menu-item w-full justify-between group">
                <span class="flex items-center gap-3">
                    <i class="{{ $item['icon'] }} w-5 text-center text-base opacity-70"></i>
                    @if (Lang::has($titleTranslation = 'admin.menu_titles.' . trim(str_replace(' ', '_', strtolower($item['title'])))))
                        <span class="sidebar-text">{{ __($titleTranslation) }}</span>
                    @else
                        <span class="sidebar-text">{{ admin_trans($item['title']) }}</span>
                    @endif
                </span>
                <svg class="w-4 h-4 collapse-icon transition-transform duration-200 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            <ul id="collapse-{{ $item['id'] }}" class="hidden ml-4 pl-3 border-l border-white/[0.08] space-y-0.5 mt-0.5">
                @foreach($item['children'] as $item)
                    @include('admin::partials.menu', $item)
                @endforeach
            </ul>
        </li>
    @endif
@endif
