<div class="flex w-full">

    @if($group)
    <div class="relative shrink-0">
        <input type="hidden" name="{{ $id }}_group" class="{{ $group_name }}-operation" value="0"/>
        <button type="button" class="glass-btn-secondary text-sm rounded-r-none whitespace-nowrap h-full" data-sa-toggle="dropdown" data-sa-target="#filter-group-{{ $id }}">
            <span class="{{ $group_name }}-label">{{ $default['label'] }}</span>
            <svg class="w-3 h-3 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <ul id="filter-group-{{ $id }}" class="absolute z-50 mt-1 bg-white/90 backdrop-blur-md border border-gray-200/50 rounded-lg shadow-glass overflow-hidden hidden {{ $group_name }}">
            @foreach($group as $index => $item)
            <li><a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50/50" href="#" data-index="{{ $index }}"> {{ $item['label'] }} </a></li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="relative flex items-center flex-1 min-w-0">
        <span class="absolute left-3 text-gray-400 pointer-events-none"><i class="icon-{{ $icon }}"></i></span>
        <input type="{{ $type }}" class="glass-input pl-8 w-full @if($group) rounded-l-none @endif {{ $id }}" placeholder="{{$placeholder}}" name="{{$name}}" value="{{ request($name, $value) }}">
    </div>
</div>
