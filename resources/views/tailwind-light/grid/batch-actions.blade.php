@if(!$holdAll)
    <div class="btn-group {{ $all }}-holder show-on-rows-selected hidden mr-1">
    <button type="button" class="glass-btn-primary text-sm dropdown-toggle" data-sa-toggle="dropdown" data-sa-target="#batch-actions-menu-{{ $all }}">
        <span class="selected" data="{{ trans('admin.grid_items_selected') }}"></span>
        <svg class="w-3 h-3 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>
    @if(!$actions->isEmpty())
    <ul id="batch-actions-menu-{{ $all }}" class="absolute z-50 mt-1 bg-white/90 backdrop-blur-md border border-gray-200/50 rounded-lg shadow-glass overflow-hidden hidden" role="menu">
        @foreach($actions as $action)
            <li class="hover:bg-gray-50/50">{!! $action->render() !!}</li>
        @endforeach
    </ul>
    @endif
  </div>
@endif
