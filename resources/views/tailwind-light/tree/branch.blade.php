<li class="dd-item" data-id="{{ $branch[$keyName] }}">
    <div class="dd-handle">
        <span class="dd-content">{!! $branchCallback($branch) !!}</span>
        <span class="dd-actions dd-nodrag">
            <a href="{{ url("$path/$branch[$keyName]/edit") }}" class="dd-action-btn dd-action-edit" title="{{ trans('admin.edit') }}"><i class="icon-edit"></i></a>
            <a onclick="admin.tree.delete({{ $branch[$keyName] }})" data-id="{{ $branch[$keyName] }}" class="tree_branch_delete dd-action-btn dd-action-delete" title="{{ trans('admin.delete') }}"><i class="icon-trash"></i></a>
        </span>
    </div>
    @if(isset($branch['children']))
    <ol class="dd-list">
        @foreach($branch['children'] as $branch)
            @include($branchView, $branch)
        @endforeach
    </ol>
    @endif
</li>
