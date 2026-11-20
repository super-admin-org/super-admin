<div class="flex">
    @if($group)
    <div class="relative">
        <input type="hidden" name="{{ $id }}_group" class="{{ $group_name }}-operation" value="0"/>
        <button type="button" class="glass-btn-secondary text-sm rounded-r-none" data-sa-toggle="dropdown" data-sa-target="#filter-dt-{{ $id }}">
            <span class="{{ $group_name }}-label">{{ $default['label'] }}</span>
            <svg class="w-3 h-3 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <ul id="filter-dt-{{ $id }}" class="absolute z-50 mt-1 bg-white/90 backdrop-blur-md border border-gray-200/50 rounded-lg shadow-glass overflow-hidden hidden {{ $group_name }}">
            @foreach($group as $index => $item)
                <li><a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50/50" data-index="{{ $index }}"> {{ $item['label'] }} </a></li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="relative flex items-center flex-1">
        <span class="absolute left-3 text-gray-400"><i class="icon-calendar"></i></span>
        <input class="glass-input pl-8 @if($group) rounded-l-none @endif" id="{{$id}}" placeholder="{{$label}}" name="{{$name}}" value="{{ request($name, $value) }}">
    </div>
</div>
