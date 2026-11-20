<div {!! $attributes !!}>
    <div class="p-4 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center text-primary-600 text-xl shrink-0">
            <i class="icon-{{ $icon }}"></i>
        </div>
        <div>
            <h3 class="text-2xl font-bold text-gray-800">{{ $info }}</h3>
            <p class="text-sm text-gray-500">{{ $name }}</p>
        </div>
    </div>
    <a href="{{ $link }}" class="block px-4 py-2 border-t border-gray-200/30 text-sm text-{{$color}}-600 hover:bg-gray-50/30 transition-colors">
        {{ $link_text }}
        <i class="icon-arrow-circle-right ml-1"></i>
    </a>
</div>
