<div {!! $attributes !!}>
    <div class="border-b border-gray-200/30">
        <nav class="flex gap-0 px-5 pt-3 overflow-x-auto glass-tabs">

            @foreach ($tabs as $i => $tab)
                @if ($tab['type'] == \SuperAdmin\Admin\Widgets\Tab::TYPE_CONTENT)
                    <a class="glass-tab {{ $active === $i ? 'active' : '' }}" href="#{{ $tab['ref'] }}"
                            data-sa-toggle="tab" data-sa-target="#{{ $tab['ref'] }}">{{ $tab['title'] }}</a>
                @elseif($tab['type'] == \SuperAdmin\Admin\Widgets\Tab::TYPE_LINK)
                    <a class="glass-tab {{ $active === $i ? 'active' : '' }}"
                            href="{{ $tab['href'] }}">{{ $tab['title'] }}</a>
                @endif
            @endforeach

            @if (!empty($dropDown))
                <div class="relative ml-2">
                    <a class="glass-tab" data-sa-toggle="dropdown" data-sa-target="#tab-dropdown-{{uniqid()}}">
                        Dropdown <svg class="w-3 h-3 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </a>
                    <ul class="absolute z-50 mt-1 bg-white/90 backdrop-blur-md border border-gray-200/50 rounded-lg shadow-glass overflow-hidden hidden min-w-36">
                        @foreach ($dropDown as $link)
                            <li><a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50/50" href="{{ $link['href'] }}">{{ $link['name'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($title)
            <span class="ml-auto text-sm text-gray-500 pb-2 self-end">{{ $title }}</span>
            @endif
        </nav>
    </div>
    <div class="tab-content">
        @foreach ($tabs as $i => $tab)
            <div class="tab-pane p-4 {{ $active === $i ? '' : 'hidden' }}" id="{{ $tab['ref'] }}">
                @php($content = \Illuminate\Support\Arr::get($tab, 'content'))
                @if ($content instanceof \Illuminate\Contracts\Support\Renderable)
                    {!! $content->render() !!}
                @else
                    {!! $content !!}
                @endif
            </div>
        @endforeach

    </div>
</div>
