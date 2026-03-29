<div {!! $attributes !!} id="{{$id}}" class="space-y-2">
    @foreach($items as $key => $item)
    <div class="glass-card overflow-hidden">
        <button type="button" class="w-full flex items-center justify-between px-5 py-3 text-left text-sm font-medium text-gray-700 hover:bg-gray-50/30 transition-colors" data-sa-toggle="collapse" data-sa-target="#collapse-{{$id}}-{{ $key }}">
            <span>{{ $item['title'] }}</span>
            <svg class="w-4 h-4 collapse-icon transition-transform {{ $key == 0 ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div id="collapse-{{$id}}-{{ $key }}" class="{{ $key == 0 ? '' : 'hidden' }} border-t border-gray-200/30">
            <div class="p-4">
                {!! $item['content'] !!}
            </div>
        </div>
    </div>
    @endforeach

</div>
