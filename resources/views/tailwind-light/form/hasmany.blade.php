
<div class="px-5 pt-4">
    <h4 class="text-sm font-semibold text-gray-700 mb-2 {{$column}}">{{ $label }}</h4>
    <hr class="border-gray-200/50">
</div>

<div id="has-many-{{$column}}" class="has-many-body has-many-{{$column}}">

    <div class="has-many-{{$column}}-forms">

        @foreach($forms as $pk => $form)

            <div class="has-many-{{$column}}-form fields-group border-b border-gray-100/50">

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
                <hr class="border-gray-100/50 mx-5">
            </div>

        @endforeach
    </div>


    <template class="{{$column}}-tpl">
        <div class="has-many-{{$column}}-form fields-group border-b border-gray-100/50">

            {!! $template !!}

            <div class="flex items-center px-5 py-3 form-delete-group">
                <div class="ml-auto">
                    <div class="remove glass-btn-danger text-sm cursor-pointer inline-flex items-center gap-1">
                        <i class="icon-trash"></i> {{ trans('admin.remove') }}
                    </div>
                </div>
            </div>
            <hr class="border-gray-100/50 mx-5">

        </div>
    </template>

    @if($options['allowCreate'])
    <div class="flex items-center px-5 py-3 has-many-footer">
        <div class="ml-auto">
            <div class="add glass-btn-success text-sm cursor-pointer inline-flex items-center gap-1">
                <i class="icon-save"></i> {{ trans('admin.new') }}
            </div>
        </div>
    </div>
    @endif

</div>
