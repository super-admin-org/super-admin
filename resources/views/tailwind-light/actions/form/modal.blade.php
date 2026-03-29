<div class="glass-modal-backdrop hidden" id="{{ $modal_id }}-backdrop" data-sa-dismiss="modal" data-sa-target="#{{ $modal_id }}-backdrop"></div>
<div class="glass-modal {{ $modal_size }}" tabindex="-1" role="dialog" id="{{ $modal_id }}">
    <div class="glass-modal-dialog">
        <div class="glass-modal-content">
            <div class="flex items-center justify-between p-5 border-b border-gray-200/30">
                <h4 class="text-base font-semibold text-gray-800">{{ $title }}</h4>
                <button type="button" class="text-gray-400 hover:text-gray-600" data-sa-dismiss="modal" data-sa-target="#{{ $modal_id }}-backdrop">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form class="form-horizontal" method="{{$method}}" action="{{$url}}" autocomplete="off" @if(!empty($multipart))enctype="multipart/form-data"@endif>
                <input type="hidden" name="_action" value="{{$_action}}">
                <input type="hidden" name="_model" value="{{$_model}}">
                <input type="hidden" name="_key" value="{{$_key}}">
                <div class="p-5 space-y-3">
                    {!! $field_html !!}
                </div>
                <div class="flex items-center justify-end gap-3 p-5 border-t border-gray-200/30">
                    <button type="button" class="glass-btn-secondary" data-sa-dismiss="modal" data-sa-target="#{{ $modal_id }}-backdrop">{{ __('admin.close') }}</button>
                    <button type="submit" class="glass-btn-primary">{{ __('admin.submit') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
