<div class="flex items-center gap-3">
    <label class="text-sm text-gray-600 shrink-0 w-28 text-right">{{$label}}&nbsp;(&gt;)</label>
    <div class="flex-1">
        @include($presenter->view())
    </div>
</div>
