<div class="border-b border-gray-200/30">
    <nav class="flex gap-0 px-5 pt-3 overflow-x-auto glass-tabs">

        @foreach($tabObj->getTabs() as $tab)
            <a class="glass-tab {{ $tab['active'] ? 'active' : '' }}" href="#tab-{{ $tab['id'] }}" data-sa-toggle="tab" data-sa-target="#tab-panes">
                {{ $tab['title'] }} <i class="icon-exclamation-circle text-red-400 ml-1 hidden"></i>
            </a>
        @endforeach

    </nav>
</div>
<div id="tab-panes" class="fields-group">

    @foreach($tabObj->getTabs() as $tab)
        <div class="tab-pane {{ $tab['active'] ? '' : 'hidden' }}" id="tab-{{ $tab['id'] }}">
            @foreach($tab['fields'] as $field)
                {!! $field->render() !!}
            @endforeach
        </div>
    @endforeach

</div>
