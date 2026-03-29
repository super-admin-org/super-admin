<div class="flex gap-2 justify-end mb-3">
    <a href="" class="glass-btn-primary text-sm" data-sa-toggle="modal" data-sa-target="#{{ $modalID }}">
        <i class="icon-filter mr-1"></i>{{ trans('admin.filter') }}
    </a>
    <a href="{!! $action !!}" class="glass-btn-secondary text-sm">
        <i class="icon-undo mr-1"></i>{{ trans('admin.reset') }}
    </a>
</div>

<div class="glass-modal-backdrop hidden" id="{{ $modalID }}-backdrop" data-sa-dismiss="modal" data-sa-target="#{{ $modalID }}-backdrop"></div>
<div class="glass-modal hidden" id="{{ $modalID }}" role="dialog">
    <div class="glass-modal-dialog">
        <div class="glass-modal-content">
            <div class="flex items-center justify-between p-5 border-b border-gray-200/30">
                <h4 class="text-base font-semibold text-gray-800">{{ trans('admin.filter') }}</h4>
                <button type="button" class="text-gray-400 hover:text-gray-600" data-sa-dismiss="modal" data-sa-target="#{{ $modalID }}-backdrop">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form action="{!! $action !!}" method="get" pjax-container>
                <div class="p-5 space-y-4">
                    @foreach($filters as $filter)
                        <div>
                            {!! $filter->render() !!}
                        </div>
                    @endforeach
                </div>
                <div class="flex items-center justify-end gap-3 p-5 border-t border-gray-200/30">
                    <button type="reset" class="glass-btn-secondary text-sm">{{ trans('admin.reset') }}</button>
                    <button type="submit" class="glass-btn-primary text-sm submit">{{ trans('admin.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
