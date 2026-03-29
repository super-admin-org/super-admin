<form {!! $attributes !!}>
    <div class="fields-group divide-y divide-gray-100/50">

        @foreach($fields as $field)
            {!! $field->render() !!}
        @endforeach

    </div>

    @if ($method != 'GET')
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    @endif

    @if(count($buttons) > 0)
    <div class="flex items-center justify-end gap-3 px-5 py-4 border-t border-gray-200/30">
        <div style="flex: 0 0 {{ ($width['label']/12 * 100) }}%; max-width: {{ ($width['label']/12 * 100) }}%;"></div>
        <div class="flex items-center gap-3">
            @if(in_array('reset', $buttons))
            <button type="reset" class="glass-btn-secondary">{{ trans('admin.reset') }}</button>
            @endif

            @if(in_array('submit', $buttons))
            <button type="submit" class="glass-btn-primary">{{ trans('admin.submit') }}</button>
            @endif
        </div>
    </div>
    @endif
</form>
