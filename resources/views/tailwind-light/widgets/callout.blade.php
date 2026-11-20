<div {!! $attributes !!} >
    @if(isset($title))
    <h4 class="font-semibold mb-1 text-sm">{{ $title }}</h4>
    @endif
    <div class="text-sm">{!! $content !!}</div>
</div>
