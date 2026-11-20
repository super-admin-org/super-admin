{{-- Breadcrumb --}}
@if ($breadcrumb)
<nav class="flex items-center text-sm text-gray-500 mt-2 sm:mt-0" aria-label="Breadcrumb">
    <a href="{{ admin_url('/') }}" class="hover:text-primary-600 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    </a>
    @foreach($breadcrumb as $item)
        <svg class="w-4 h-4 mx-1.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        @if($loop->last)
            <span class="text-gray-700 font-medium">{{ $item['text'] }}</span>
        @else
            @if (\Illuminate\Support\Arr::has($item, 'url'))
                <a href="{{ admin_url(\Illuminate\Support\Arr::get($item, 'url')) }}" class="hover:text-primary-600 transition-colors">{{ $item['text'] }}</a>
            @else
                <span>{{ $item['text'] }}</span>
            @endif
        @endif
    @endforeach
</nav>
@elseif(config('admin.enable_default_breadcrumb'))
<nav class="flex items-center text-sm text-gray-500 mt-2 sm:mt-0" aria-label="Breadcrumb">
    <a href="{{ admin_url('/') }}" class="hover:text-primary-600 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    </a>
    @for($i = 2; $i <= count(Request::segments()); $i++)
        <svg class="w-4 h-4 mx-1.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ admin_url(implode('/', array_slice(Request::segments(), 1, $i - 1))) }}" class="hover:text-primary-600 transition-colors">
            {{ ucfirst(Request::segment($i)) }}
        </a>
    @endfor
</nav>
@endif
