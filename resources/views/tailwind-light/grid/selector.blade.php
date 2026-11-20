<div class="grid-selector border-b border-gray-200/30">
    @foreach($selectors as $column => $selector)
        <div class="flex items-start gap-4 px-5 py-2 border-b border-gray-100/50 last:border-0 text-sm">
            <div class="w-28 text-gray-500 shrink-0 pt-0.5">{{ $selector['label'] }}</div>
            <div class="flex-1">
                <ul class="flex flex-wrap gap-x-5 gap-y-1 list-none m-0 p-0">
                    @foreach($selector['options'] as $value => $option)
                        @php
                            $active = in_array($value, \Illuminate\Support\Arr::get($selected, $column, []));
                        @endphp
                        <li class="flex items-center gap-1">
                            <a href="{{ \SuperAdmin\Admin\Grid\Tools\Selector::url($column, $value, true) }}"
                               class="{{ $active ? 'text-primary-600 font-semibold' : 'text-gray-600 hover:text-primary-500' }} transition-colors">{{ $option }}</a>
                            @if(!$active && $selector['type'] == 'many')
                                <a href="{{ \SuperAdmin\Admin\Grid\Tools\Selector::url($column, $value) }}" class="text-gray-300 hover:text-primary-400 transition-colors">
                                    <i class="icon-plus-square text-xs"></i>
                                </a>
                            @endif
                        </li>
                    @endforeach
                    <li>
                        <a href="{{ \SuperAdmin\Admin\Grid\Tools\Selector::url($column) }}" class="text-gray-300 hover:text-red-400 transition-colors">
                            <i class="icon-trash text-xs"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    @endforeach
</div>
