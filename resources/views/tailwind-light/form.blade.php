<div class="glass-card">
    <div class="flex items-center justify-between px-5 py-3 border-b border-gray-200/30">
        <h3 class="text-sm font-semibold text-gray-700">{{ $form->title() }}</h3>
        <div class="flex items-center gap-2">
            {!! $form->renderTools() !!}
        </div>
    </div>

    {!! $form->open() !!}

    <div class="p-0">

        @if(!$tabObj->isEmpty())
            @include('admin::form.tab', compact('tabObj'))
        @else
            <div class="flex flex-wrap fields-group">

                @if($form->hasRows())
                    @foreach($form->getRows() as $row)
                        {!! $row->render() !!}
                    @endforeach
                @else
                    @foreach($layout->columns() as $column)
                        <div style="flex: 0 0 {{ ($column->width()/12 * 100) }}%; max-width: {{ ($column->width()/12 * 100) }}%;">
                            @foreach($column->fields() as $field)
                                {!! $field->render() !!}
                            @endforeach
                        </div>
                    @endforeach
                @endif
            </div>
        @endif

    </div>

    {!! $form->renderFooter() !!}

    @foreach($form->getHiddenFields() as $field)
        {!! $field->render() !!}
    @endforeach

    {!! $form->close() !!}

</div>
