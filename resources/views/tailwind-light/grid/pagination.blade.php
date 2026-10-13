<div class="flex items-center justify-between px-5 py-3 border-t border-gray-200/30 flex-wrap gap-3 @if (!empty($fixedFooter))sticky bottom-0 bg-white/85 backdrop-blur-md shadow-[0_-4px_16px_rgba(0,0,0,0.06)] rounded-b-glass z-30 @endif">
    <div class="flex-1 text-sm text-gray-500">{!!$range!!}</div>
    <div class="shrink-0">{!!$per_page!!}</div>
    <div>{!!$links!!}</div>
</div>
