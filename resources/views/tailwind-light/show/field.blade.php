<div class="flex flex-wrap items-start px-5 py-3">
    <label class="text-sm font-medium text-gray-500 w-32 shrink-0 pt-0.5">{{ $label }}</label>
    <div class="flex-1 min-w-0 show-value">
        @if($wrapped)
        <div class="glass-card p-3">
            @if($escape)
                {{ $content }}&nbsp;
            @else
                {!! $content !!}&nbsp;
            @endif
        </div>
        @else
            <div class="text-sm text-gray-800">
            @if($escape)
                {{ $content }}
            @else
                {!! $content !!}
            @endif
            </div>
        @endif
    </div>
</div>
