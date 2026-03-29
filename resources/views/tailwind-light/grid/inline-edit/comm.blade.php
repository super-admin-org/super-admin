{{--inline edit popover--}}

<span class="ie-wrap">
    <a
        id="{{ $trigger }}"
        class="ie"
        data-bs-toggle="popover"
        data-target="{{ $target }}"
        data-value="{{ $value }}"
        data-original="{{ $value }}"
        data-key="{{ $key }}"
        data-name="{{ $name }}"
        data-resource="{{ $resource }}"
        @isset($type)
        data-type="{{ $type }}"
        @endisset
        data-init="0"
    >
        <span class="ie-display">{{ $display }}</span>
        <i class="icon-edit" style="visibility: hidden;"></i>
    </a>
</span>

<template id="{{ $target }}">
    <div class="ie-content ie-content-{{ $name }}">
        <div class="ie-container">
            @yield('field')
            <div class="error text-red-500 text-xs mt-1"></div>
        </div>
        <div class="ie-action flex gap-2 mt-2">
            <button class="glass-btn-primary text-xs py-1 px-3 ie-submit">{{ __('admin.submit') }}</button>
            <button class="glass-btn-secondary text-xs py-1 px-3 ie-cancel">{{ __('admin.cancel') }}</button>
        </div>
    </div>
</template>

<script>
    admin.grid.inline_edit.init_popover("{{$trigger}}","{{$target}}");
</script>

@yield('assert')
