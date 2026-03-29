<thead>
<tr class="quick-create bg-gray-50/30">
    <td colspan="{{ $columnCount }}" style="height: 60px; padding-left: 50px; vertical-align: middle;">

        <span class="create text-gray-400 cursor-pointer inline-block hover:text-primary-500 transition-colors">
             <i class="icon-plus"></i>&nbsp;{{ __('admin.quick_create') }}
        </span>

        <form class="flex items-center gap-3 create-form" autocomplete="off" style="display: none; width: calc(100% - 50px);" method="post" action='{{$url}}'>
            @foreach($fields as $field)
                {!! $field->render() !!}
            @endforeach

            <div class="flex items-center gap-2 shrink-0">
                <button class="glass-btn-primary text-sm">{{ __('admin.submit') }}</button>
                <a href="javascript:void(0);" class="cancel text-sm text-gray-500 hover:text-gray-700">{{ __('admin.cancel') }}</a>
            </div>
            {{ csrf_field() }}
        </form>
    </td>
</tr>
</thead>
