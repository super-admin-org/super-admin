<div class="{{$viewClass['form-group']}} flex flex-wrap items-start px-5 py-3">

    <label class="{{$viewClass['label']}} text-sm font-medium text-gray-600 pt-2 shrink-0">{{$label}}</label>

    <div class="{{$viewClass['field']}} flex-1 min-w-0">
        <table class="glass-table">
            <thead>
            <tr>
                @if(!empty($options['sortable']))
                    <th class="w-8"></th>
                @endif
                <th>{{ __('Key') }}</th>
                <th>{{ __('Value') }}</th>
                <th class="w-20"></th>
            </tr>
            </thead>
            <tbody class="kv-{{$column}}-table">

            @foreach(old("{$column}.keys", ($value ?: [])) as $k => $v)

                @php($keysErrorKey = "{$column}.keys.{$loop->index}")
                @php($valsErrorKey = "{$column}.values.{$loop->index}")

                <tr>
                    @if(!empty($options['sortable']))
                        <td><span class="icon-arrows-alt-v cursor-grab text-gray-400 handle"></span></td>
                    @endif
                    <td>
                        <input name="{{ $name }}[keys][]" value="{{ old("{$column}.keys.{$k}", $k) }}" class="glass-input text-sm {{ $errors->has($keysErrorKey) ? 'border-red-400' : '' }}" required/>
                        @if($errors->has($keysErrorKey))
                            @foreach($errors->get($keysErrorKey) as $message)
                                <span class="text-xs text-red-500">{{$message}}</span>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        <input name="{{ $name }}[values][]" value="{{ old("{$column}.values.{$k}", $v) }}" class="glass-input text-sm {{ $errors->has($valsErrorKey) ? 'border-red-400' : '' }}"/>
                        @if($errors->has($valsErrorKey))
                            @foreach($errors->get($valsErrorKey) as $message)
                                <span class="text-xs text-red-500">{{$message}}</span>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        <div class="{{$column}}-remove glass-btn-danger text-xs cursor-pointer inline-flex items-center gap-1">
                            <i class="icon-trash"></i> {{ __('admin.remove') }}
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>
        <div class="mt-2">
            <div class="{{ $column }}-add glass-btn-success text-sm cursor-pointer inline-flex items-center gap-1">
                <i class="icon-plus"></i> {{ __('admin.new') }}
            </div>
        </div>

    </div>
    <template class="{{$column}}-tpl">
        <tr>
            @if(!empty($options['sortable']))
                <td><span class="icon-arrows-alt-v cursor-grab text-gray-400 handle"></span></td>
            @endif
            <td><input name="{{ $name }}[keys][]" class="glass-input text-sm" required/></td>
            <td><input name="{{ $name }}[values][]" class="glass-input text-sm"/></td>
            <td>
                <div class="{{$column}}-remove glass-btn-danger text-xs cursor-pointer inline-flex items-center gap-1">
                    <i class="icon-trash"></i> {{ __('admin.remove') }}
                </div>
            </td>
        </tr>
    </template>
</div>
