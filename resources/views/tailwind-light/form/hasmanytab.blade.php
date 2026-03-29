<div id="has-many-{{$column}}" class="has-many-{{$column}}">
    <div class="px-5 pt-4">
        <h4 class="text-sm font-semibold text-gray-700 mb-2">{{ $label }}</h4>
    </div>
    <hr class="border-gray-200/50 mx-5">

    <div class="border-b border-gray-200/30">
        <nav class="flex gap-0 px-5 pt-3 overflow-x-auto glass-tabs">
            @foreach($forms as $pk => $form)
                <a class="glass-tab @if ($form == reset($forms)) active @endif" href="#{{ $relationName . '_' . $pk }}" data-sa-toggle="tab" data-sa-target="#tab-panes-{{$column}}" id="tab_{{ $relationName . '_' . $pk }}">
                    {{ $pk }} <i class="icon-exclamation-circle text-red-400 ml-1 hidden"></i>
                </a>
            @endforeach
            <button type="button" class="glass-btn-secondary text-xs px-2 py-1 mb-2 ml-2 add"><i class="icon-plus-circle"></i></button>
        </nav>
    </div>

    <div id="tab-panes-{{$column}}" class="tab-content has-many-{{$column}}-forms">
        @foreach($forms as $pk => $form)
            <div class="tab-pane fields-group has-many-{{$column}}-form @if ($form == reset($forms)) @else hidden @endif" id="{{ $relationName . '_' . $pk }}">
                @foreach($form->fields() as $field)
                    {!! $field->render() !!}
                @endforeach

                @if($options['allowDelete'])
                <div class="flex items-center px-5 py-3 form-delete-group">
                    <div class="ml-auto">
                        <div class="remove glass-btn-danger text-sm cursor-pointer inline-flex items-center gap-1">
                            <i class="icon-trash"></i> {{ trans('admin.remove') }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        @endforeach
    </div>

    <template class="{{$column}}-tab-tpl">
        <a class="glass-tab new" href="#{{ $relationName . '_new_' . \SuperAdmin\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}" data-sa-toggle="tab" data-sa-target="#tab-panes-{{$column}}" id="tab_{{ $relationName . '_new_' . \SuperAdmin\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}">
            &nbsp;New {{ \SuperAdmin\Admin\Form\NestedForm::DEFAULT_KEY_NAME }} <i class="icon-exclamation-circle text-red-400 ml-1 hidden"></i>
        </a>
    </template>
    <template class="{{$column}}-tpl">
        <div class="tab-pane fields-group new" id="{{ $relationName . '_new_' . \SuperAdmin\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}">
            {!! $template !!}
            @if($options['allowDelete'])
            <div class="flex items-center px-5 py-3 form-delete-group">
                <div class="ml-auto">
                    <div class="remove glass-btn-danger text-sm cursor-pointer inline-flex items-center gap-1">
                        <i class="icon-trash"></i> {{ trans('admin.remove') }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </template>

</div>
