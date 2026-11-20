<div {!! $attributes !!} class="relative overflow-hidden rounded-xl border border-white/30" style='width:{{ $width }}px;'>
    <div class="relative" id="{{ $id }}-slides">
        @foreach($items as $key => $item)
        <div class="carousel-item {{ $key == 0 ? '' : 'hidden' }}" data-index="{{ $key }}">
            <img src="{{ url($item['image']) }}" alt="{{$item['caption']}}" style='max-width:{{ $width }}px;max-height:{{ $height }}px;display:block;margin:auto;'>
            @if($item['caption'])
            <div class="absolute bottom-0 left-0 right-0 bg-black/40 text-white text-sm text-center py-2 px-4 backdrop-blur-sm">
                {{$item['caption']}}
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <button class="absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white/30 backdrop-blur-sm flex items-center justify-center text-white hover:bg-white/50 transition-colors" onclick="
        var slides = this.closest('.relative').querySelectorAll('.carousel-item');
        var current = Array.from(slides).findIndex(s=>!s.classList.contains('hidden'));
        slides[current].classList.add('hidden');
        slides[(current-1+slides.length)%slides.length].classList.remove('hidden');
    ">&#8249;</button>
    <button class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white/30 backdrop-blur-sm flex items-center justify-center text-white hover:bg-white/50 transition-colors" onclick="
        var slides = this.closest('.relative').querySelectorAll('.carousel-item');
        var current = Array.from(slides).findIndex(s=>!s.classList.contains('hidden'));
        slides[current].classList.add('hidden');
        slides[(current+1)%slides.length].classList.remove('hidden');
    ">&#8250;</button>

    <div class="absolute bottom-8 left-0 right-0 flex justify-center gap-1">
        @foreach($items as $key => $item)
        <button class="w-2 h-2 rounded-full bg-white/60 hover:bg-white transition-colors" onclick="
            var slides = this.closest('.relative').querySelectorAll('.carousel-item');
            slides.forEach(s=>s.classList.add('hidden'));
            slides[{{ $key }}].classList.remove('hidden');
        "></button>
        @endforeach
    </div>
</div>
