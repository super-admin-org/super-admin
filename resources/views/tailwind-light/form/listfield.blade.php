
@php($listErrorKey = "$column")
@include("admin::form._header")

        <table class="glass-table">
            <tbody class="list-{{$column}}-table">
            @foreach(old("{$column}", ($value ?: [])) as $k => $v)
                @php($itemErrorKey = "{$column}.{$loop->index}")
                <tr>
                    @if(!empty($options['sortable']))
                        <td class="w-8"><span class="icon-arrows-alt-v cursor-grab text-gray-400 handle"></span></td>
                    @endif
                    <td>
                        <input name="{{ $column }}[]" value="{{ old("{$column}.{$k}", $v) }}" class="glass-input text-sm {{ $errors->has($itemErrorKey) ? 'border-red-400' : '' }}" />
                        @if($errors->has($itemErrorKey))
                            @foreach($errors->get($itemErrorKey) as $message)
                                <span class="text-xs text-red-500">{{$message}}</span>
                            @endforeach
                        @endif
                    </td>
                    <td class="w-20">
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

        <template class="{{$column}}-tpl">
            <tr>
                @if(!empty($options['sortable']))
                    <td class="w-8"><span class="icon-arrows-alt-v cursor-grab text-gray-400 handle"></span></td>
                @endif
                <td><input name="{{ $column }}[]" class="glass-input text-sm" /></td>
                <td class="w-20">
                    <div class="{{$column}}-remove glass-btn-danger text-xs cursor-pointer inline-flex items-center gap-1">
                        <i class="icon-trash"></i> {{ __('admin.remove') }}
                    </div>
                </td>
            </tr>
        </template>

@include("admin::form._footer")
