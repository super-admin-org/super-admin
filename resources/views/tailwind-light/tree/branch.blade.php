<li class="dd-item" data-id="{{ $branch[$keyName] }}">
    <div class="dd-handle flex items-center justify-between px-3 py-2">
        <span>{!! $branchCallback($branch) !!}</span>
        <span class="flex items-center gap-2 dd-nodrag">
            <a href="{{ url("$path/$branch[$keyName]/edit") }}" class="text-gray-400 hover:text-primary-500 transition-colors"><i class="icon-edit text-sm"></i></a>
            <a onclick="admin.tree.delete({{ $branch[$keyName] }})" data-id="{{ $branch[$keyName] }}" class="tree_branch_delete text-gray-400 hover:text-red-500 transition-colors cursor-pointer"><i class="icon-trash text-sm"></i></a>
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
