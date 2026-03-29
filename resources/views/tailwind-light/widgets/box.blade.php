<div {!! $attributes !!}>
    @if($title || $tools)
        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-200/30">
            <h3 class="text-sm font-semibold text-gray-700">{{ $title }}</h3>
            <div class="flex items-center gap-2">
                @foreach($tools as $tool)
                    {!! $tool !!}
                @endforeach
            </div>
        </div>
    @endif
    <div id="{{$id}}-body" class="p-4">
        {!! $content !!}
    </div>
    @if($footer)
        <div class="px-5 py-3 border-t border-gray-200/30">
            {!! $footer !!}
        </div>
    @endif
</div>
<script>
    {!! $script !!}
</script>
