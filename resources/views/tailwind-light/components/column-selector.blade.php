<div class="relative inline-block grid-column-selector" id="grid-column-selector" data-defaults='{{ implode(",",$defaults) }}'>
    <button type="button" class="glass-btn-primary text-sm" data-sa-toggle="dropdown" data-sa-target="#column-selector-menu">
        <i class="icon-table"></i>
        <svg class="w-3 h-3 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>
    <ul id="column-selector-menu" class="absolute right-0 z-50 mt-1 bg-white/90 backdrop-blur-md border border-gray-200/50 rounded-lg shadow-glass overflow-hidden hidden min-w-48">

        @foreach($columns as $key => $label)
        @php
        if (empty($visible)) {
            $checked = 'checked';
        } else {
            $checked = in_array($key, $visible) ? 'checked' : '';
        }
        @endphp

        <li>
            <label class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50/50 cursor-pointer" for="column-select-{{ $key }}">
                <input type="checkbox" class="rounded border-gray-300 text-primary-500 column-selector" id="column-select-{{ $key }}" value="{{ $key }}" {{ $checked }}/> {{ $label }}
            </label>
        </li>
        @endforeach

        <li><hr class="border-gray-200/50 my-1"></li>
        <li class="flex items-center justify-end gap-2 px-4 py-2">
            <button class="glass-btn-secondary text-xs px-2 py-1 column-select-all" onclick="admin.grid.columns.all()">{{ __('admin.all') }}</button>
            <button class="glass-btn-primary text-xs px-2 py-1 column-select-submit" onclick="admin.grid.columns.submit()">{{ __('admin.submit') }}</button>
        </li>
    </ul>
</div>
