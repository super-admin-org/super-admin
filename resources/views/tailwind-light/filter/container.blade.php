<div class="{{ $expand ? '' : 'hidden' }} filter-box border-b border-gray-200/30" id="{{ $filterID }}">
    <form action="{!! $action !!}" class="pt-0" pjax-container method="get" autocomplete="off">

        <div class="flex flex-wrap">
            @foreach($layout->columns() as $column)
            <div style="flex: 0 0 {{ ($column->width()/12 * 100) }}%; max-width: {{ ($column->width()/12 * 100) }}%;">
                <div class="px-5 py-3">
                    <div class="fields-group space-y-3">
                        @foreach($column->filters() as $filter)
                            {!! $filter->render() !!}
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="px-5 py-3 border-t border-gray-200/30 flex items-center gap-3">
            <button class="glass-btn-primary text-sm submit">
                <i class="icon-search mr-1"></i>{{ trans('admin.search') }}
            </button>
            <a href="{!! $action !!}" class="glass-btn-secondary text-sm">
                <i class="icon-undo mr-1"></i>{{ trans('admin.reset') }}
            </a>
        </div>

    </form>
</div>
