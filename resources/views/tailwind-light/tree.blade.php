<div class="glass-card overflow-hidden">

    <div class="flex items-center gap-2 px-5 py-3 border-b border-gray-200/30 flex-wrap">
        <div class="flex gap-2">
            <a class="glass-btn-primary text-sm {{ $id }}-tree-tools" data-action="expand" title="{{ trans('admin.expand') }}" onclick="admin.tree.expand();">
                <i class="icon-plus-square mr-1"></i>{{ trans('admin.expand') }}
            </a>
            <a class="glass-btn-secondary text-sm {{ $id }}-tree-tools" data-action="collapse" title="{{ trans('admin.collapse') }}" onclick="admin.tree.collapse();">
                <i class="icon-minus-square mr-1"></i>{{ trans('admin.collapse') }}
            </a>
        </div>

        @if($useSave)
        <a class="glass-btn-primary text-sm {{ $id }}-save" title="{{ trans('admin.save') }}" onclick="admin.tree.save();">
            <i class="icon-save mr-1"></i>{{ trans('admin.save') }}
        </a>
        @endif

        @if($useRefresh)
        <a class="glass-btn-secondary text-sm {{ $id }}-refresh" title="{{ trans('admin.refresh') }}" onclick="admin.ajax.reload();">
            <i class="icon-refresh mr-1"></i>{{ trans('admin.refresh') }}
        </a>
        @endif

        <div class="flex gap-2">
            {!! $tools !!}
        </div>

        @if($useCreate)
        <div class="ml-auto">
            <a class="glass-btn-success text-sm" href="{{ url($path) }}/create">
                <i class="icon-save mr-1"></i>{{ trans('admin.new') }}
            </a>
        </div>
        @endif

    </div>

    <div class="p-4">
        <div class="dd" id="{{ $id }}">
            <ol class="dd-list">
                @each($branchView, $items, 'branch')
            </ol>
        </div>
    </div>
</div>
