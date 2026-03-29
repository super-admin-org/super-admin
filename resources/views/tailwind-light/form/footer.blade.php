
<div class="flex items-center justify-between px-5 py-4 border-t border-gray-200/30 @if (!empty($fixedFooter))fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-md shadow-lg z-30 @endif">
    {{ csrf_field() }}

    <div style="flex: 0 0 {{ ($width['label']/12 * 100) }}%; max-width: {{ ($width['label']/12 * 100) }}%;">
    </div>

    <div class="flex items-center gap-3">
        @if(in_array('reset', $buttons))
        <button type="reset" class="glass-btn-secondary">{{ trans('admin.reset') }}</button>
        @endif

        @if(in_array('submit', $buttons))

        <div class="flex items-center gap-3">
        @foreach($submit_redirects as $value => $redirect)
            @if(in_array($redirect, $checkboxes))
            <label class="inline-flex items-center gap-2 cursor-pointer">
                <input type="checkbox" class="rounded border-gray-300 text-primary-500 focus:ring-primary-400 after-submit" id="after-save-{{$redirect}}" name="after-save" value="{{ $value }}" {{ ($default_check == $redirect) ? 'checked' : '' }}>
                <span class="text-sm text-gray-700">{{ trans("admin.{$redirect}") }}</span>
            </label>
            @endif
        @endforeach

            <button type="submit" class="glass-btn-primary">{{ trans('admin.submit') }}</button>
        </div>

        @endif
    </div>
</div>
