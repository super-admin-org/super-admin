
<div class="glass-card">
    @if(isset($title))
        <div class="px-5 py-3 border-b border-gray-200/30">
            <h3 class="text-sm font-semibold text-gray-700"> {{ $title }}</h3>
        </div>
    @endif

    <div class="px-5 py-3 border-b border-gray-200/30">
        @if ( $grid->showTools() || $grid->showExportBtn() || $grid->showCreateBtn() )
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-2 flex-wrap">
                {!! $grid->renderCreateButton() !!}
                @if ( $grid->showTools() )
                {!! $grid->renderHeaderTools() !!}
                @endif
            </div>
            <div class="flex items-center gap-2">
                {!! $grid->renderExportButton() !!}
                {!! $grid->renderColumnSelector() !!}
            </div>
        </div>
        @endif
    </div>
    {!! $grid->renderFilter() !!}
    {!! $grid->renderHeader() !!}
