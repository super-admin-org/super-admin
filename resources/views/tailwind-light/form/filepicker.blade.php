<div class="{{$viewClass['form-group']}} flex flex-wrap items-start px-5 py-3 {!! !$errors->has($errorKey) ? '' : 'bg-red-50/30' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} text-sm font-medium text-gray-600 pt-2 shrink-0">{{$label}}</label>

    <div class="{{$viewClass['field']}} flex-1 min-w-0 picker-{{ $column }}">

        @include('admin::form.error')

        <div class="picker-file-preview flex flex-wrap gap-2 mb-3 {{ empty($preview) ? 'hidden' : '' }}">
            @foreach($preview as $item)
            <div class="file-preview-frame border border-gray-200/60 rounded-lg overflow-hidden bg-white/60" data-val="{!! $item['value'] !!}">
                <div class="file-content flex items-center justify-center h-20 w-24">
                    @if($item['is_file'])
                        <i class="icon-file-text-o text-3xl text-gray-400"></i>
                    @else
                        <img src="{{ $item['url'] }}" class="max-h-20 max-w-24 object-contain"/>
                    @endif
                </div>
                <div class="text-xs text-gray-500 px-2 py-1 truncate max-w-24">{{ basename($item['url']) }}</div>
                <div class="flex gap-1 px-2 pb-2">
                    <a class="glass-btn-secondary text-xs px-2 py-0.5 remove"><i class="icon-trash"></i></a>
                    <a class="glass-btn-secondary text-xs px-2 py-0.5" target="_blank" download="{{ basename($item['url']) }}" href="{{ $item['url'] }}"><i class="icon-download"></i></a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="flex gap-2">
            <input {!! $attributes !!} class="glass-input flex-1" />
            <span class="flex">{!! $btn !!}</span>
        </div>
@include("admin::form._footer")

<template>
    <template id="file-preview">
        <div class="file-preview-frame border border-gray-200/60 rounded-lg overflow-hidden bg-white/60" data-val="_val_">
            <div class="file-content flex items-center justify-center h-20 w-24"><i class="icon-file-text-o text-3xl text-gray-400"></i></div>
            <div class="text-xs text-gray-500 px-2 py-1 truncate max-w-24">_name_</div>
            <div class="flex gap-1 px-2 pb-2">
                <a class="glass-btn-secondary text-xs px-2 py-0.5 remove"><i class="icon-trash"></i></a>
                <a class="glass-btn-secondary text-xs px-2 py-0.5" target="_blank" download="_name_" href="_url_"><i class="icon-download"></i></a>
            </div>
        </div>
    </template>
    <template id="image-preview">
        <div class="file-preview-frame border border-gray-200/60 rounded-lg overflow-hidden bg-white/60" data-val="_val_">
            <div class="file-content flex items-center justify-center h-20 w-24"><img src="_url_" class="max-h-20 object-contain"></div>
            <div class="text-xs text-gray-500 px-2 py-1 truncate max-w-24">_name_</div>
            <div class="flex gap-1 px-2 pb-2">
                <a class="glass-btn-secondary text-xs px-2 py-0.5 remove"><i class="icon-trash"></i></a>
                <a class="glass-btn-secondary text-xs px-2 py-0.5" target="_blank" download="_name_" href="_url_"><i class="icon-download"></i></a>
            </div>
        </div>
    </template>
</template>
