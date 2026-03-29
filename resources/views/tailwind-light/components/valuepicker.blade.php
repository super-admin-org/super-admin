
<template render="true">
    <div class="glass-modal-backdrop hidden" id="{{ $modal }}-backdrop"></div>
    <div class="glass-modal picker hidden" id="{{ $modal }}" tabindex="-1" role="dialog">
        <div class="glass-modal-dialog glass-modal-lg">
            <div class="glass-modal-content">
                <div class="flex items-center justify-between p-5 border-b border-gray-200/30">
                    <h4 class="text-base font-semibold text-gray-800">{{ admin_trans('admin.choose') }}</h4>
                    <button type="button" class="text-gray-400 hover:text-gray-600" data-sa-dismiss="modal" data-sa-target="#{{ $modal }}-backdrop">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="p-5 modal-body">
                    <div class="loading text-center py-10">
                        <i class="icon-spinner icon-3x text-gray-400"></i>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 p-5 border-t border-gray-200/30">
                    <button type="button" class="glass-btn-secondary" data-sa-dismiss="modal" data-sa-target="#{{ $modal }}-backdrop">{{ admin_trans('admin.cancel') }}</button>
                    <button type="button" class="glass-btn-primary submit">{{ admin_trans('admin.submit') }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

var pickInput = document.querySelector("{{ $selector }}");
var separator = '{{ $separator }}';
var value;
var refresh = function () {};

@if($multiple)

    var getValue = function () {
        let value = (new String(pickInput.value)).split(separator).filter(function (val) {
            return val != '';
        });
        return value;

    };
    var setValue = function (values,rows) {
        pickInput.value = values.join(separator);
    };

@else

    var getValue = function () {
        value = pickInput.value;
    };
    var setValue = function (values,rows) {
        pickInput.value = values[0];
    };

@endif

var config = {
    modal_elm : document.querySelector('#{{ $modal }}'),
    url : "{!! $url !!}",
    update : setValue,
    value : getValue
}

admin.selectable.init(config);

getValue();

</script>
