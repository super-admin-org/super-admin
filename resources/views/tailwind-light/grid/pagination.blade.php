<div class="flex items-center justify-between px-5 py-3 border-t border-gray-200/30 flex-wrap gap-3 @if (!empty($fixedFooter))fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-md shadow-lg z-30 @endif">
    <div class="flex-grow-1">{!!$range!!}</div>
    <div class="text-sm text-gray-500">{!!$per_page!!}</div>
    <div>{!!$links!!}</div>
</div>
