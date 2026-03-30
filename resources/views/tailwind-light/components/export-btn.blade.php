<div class="relative inline-flex items-stretch mr-1">
    <a href="{{$grid->getExportUrl('all')}}" target="_blank" class="glass-btn-primary text-sm inline-flex items-center rounded-r-none" title="{{trans('admin.export')}}">
        <i class="icon-download mr-1"></i>{{trans('admin.export')}}
    </a>
    <button type="button" class="glass-btn-primary text-sm rounded-l-none border-l border-primary-400 px-2" data-sa-toggle="dropdown" data-sa-target="#export-dropdown">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>
    <ul id="export-dropdown" class="absolute right-0 z-50 mt-1 bg-white/90 backdrop-blur-md border border-gray-200/50 rounded-lg shadow-glass overflow-hidden hidden min-w-36">
        <li><a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50/50" href="{{$grid->getExportUrl('all')}}" target="_blank">{{trans('admin.all')}}</a></li>
        <li><a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50/50" href="{{$grid->getExportUrl('page', $page)}}" target="_blank">{{trans('admin.current_page')}}</a></li>
        <li><a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50/50" href="{{$grid->getExportUrl('selected', '__rows__')}}" target="_blank" onclick="admin.grid.export_selected_row(event);" data-no_rows_selected="{{__('admin.no_rows_selected')}}">{{trans('admin.selected_rows')}}</a></li>
    </ul>
</div>
