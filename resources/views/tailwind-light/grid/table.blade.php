@include("admin::grid.table-header")

        <div class="overflow-x-auto" autocomplete="off">
            <table class="glass-table" id="{{ $grid->tableID }}">

                <thead>
                    <tr>
                        @foreach($grid->visibleColumns() as $column)
                        <th {!! $column->formatHtmlAttributes() !!}>{!! $column->getLabel() !!}{!! $column->renderHeader() !!}</th>
                        @endforeach
                    </tr>
                </thead>

                @if ($grid->hasQuickCreate())
                    {!! $grid->renderQuickCreate() !!}
                @endif

                <tbody>

                    @if($grid->rows()->isEmpty() && $grid->showDefineEmptyPage())
                        @include('admin::grid.empty-grid')
                    @endif

                    @foreach($grid->rows() as $row)
                    <tr {!! $row->getRowAttributes() !!}>
                        @foreach($grid->visibleColumnNames() as $name)
                        <td {!! $row->getColumnAttributes($name) !!}>
                            {!! $row->column($name) !!}
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>

                {!! $grid->renderTotalRow() !!}

            </table>

        </div>

        {!! $grid->renderFooter() !!}

        {!! $grid->paginator() !!}

    </div>
</div>
