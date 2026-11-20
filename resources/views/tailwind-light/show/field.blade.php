<div class="show-field">
    <div class="show-field-label">{{ $label }}</div>
    <div class="show-field-value">
        @if($wrapped)
        <div class="show-field-wrapped">
            @if($escape)
                {{ $content }}&nbsp;
            @else
                {!! $content !!}&nbsp;
            @endif
        </div>
        @else
            @if($escape)
                {{ $content }}
            @else
                {!! $content !!}
            @endif
        @endif
    </div>
</div>
