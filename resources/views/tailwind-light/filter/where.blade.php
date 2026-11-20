<div class="flex items-start gap-3">
    <label class="text-sm font-medium text-gray-600 shrink-0 pt-2 text-right" style="min-width:6rem;max-width:8rem">{{$label}}</label>
    <div class="flex-1 min-w-0">
        @include($presenter->view())
    </div>
</div>
