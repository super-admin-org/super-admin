
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
                <div class="hidden flex items-center justify-end gap-3 p-5 border-t border-gray-200/30">
                    <button type="button" class="glass-btn-secondary" data-sa-dismiss="modal" data-sa-target="#{{ $modal }}-backdrop">{{ admin_trans('admin.cancel') }}</button>
                    <button type="button" class="glass-btn-primary submit">{{ admin_trans('admin.submit') }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    window.setFile{{$selector}} = function (url,fileName){
        FileUpload_{{$name}}.addFileFromUrl(url);

        @if (empty($multiple))
            admin.selectable.hideModal();
        @else
            admin.toast("File added");
        @endif
    }

    var url = "/admin/media?select=true&fn=setFile{{$selector}}{!!$picker_path!!}";
    var config = {
        url : url,
        modal_elm : document.querySelector('#{{$modal}}'),
    }
    admin.selectable.init(config);


</script>

<style>
    #{{$modal}} .glass-card .glass-header{
        display:none;
    }
</style>
