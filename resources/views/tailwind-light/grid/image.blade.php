<div class="glass-card overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-200/30"></div>

    {!! $grid->renderFilter() !!}

    <div class="p-4">
        <ul class="flex flex-wrap gap-3 p-0">
            @foreach($grid->rows() as $row)
            <li class="list-none">
                <label class="cursor-pointer">
                    {!! $row->column($key) !!}
                    {!! $row->column('__modal_selector__') !!}
                </label>
            </li>
            @endforeach
        </ul>
    </div>

    <div class="px-5 py-3 border-t border-gray-200/30">
        {!! $grid->paginator() !!}
    </div>
</div>
