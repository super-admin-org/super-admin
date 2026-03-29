@include("admin::form._header")

        <div id="has-many-{{$column}}">
            <table class="glass-table has-many-{{$column}} vertical-align-{{$verticalAlign}}">
                <thead>
                <tr>
                    @if(!empty($options['sortable']))
                        <th class="w-8"></th>
                    @endif

                    @foreach($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach

                    <th class="hidden"></th>

                    @if($options['allowDelete'])
                        <th class="w-16"></th>
                    @endif
                </tr>
                </thead>
                <tbody class="has-many-{{$column}}-forms">
                @foreach($forms as $pk => $form)
                    <tr class="has-many-{{$column}}-form fields-group">

                        @if(!empty($options['sortable']))
                           <td><span class="icon-arrows-alt-v glass-btn-secondary text-xs px-2 py-1 handle cursor-grab"></span></td>
                        @endif

                        <?php $hidden = ''; ?>

                        @foreach($form->fields() as $field)

                            @if (is_a($field, \SuperAdmin\Admin\Form\Field\Hidden::class))
                                <?php $hidden .= $field->render(); ?>
                                @continue
                            @endif

                            <td>{!! $field->setLabelClass(['hidden'])->setWidth(12, 0)->render() !!}</td>
                        @endforeach

                        <td class="hidden">{!! $hidden !!}</td>

                        @if($options['allowDelete'])
                            <td>
                                <div class="remove glass-btn-danger text-xs cursor-pointer inline-flex items-center gap-1">
                                    <i class="icon-trash"></i> {{ trans('admin.remove') }}
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>

            <template class="{{$column}}-tpl">
                <tr class="has-many-{{$column}}-form fields-group">

                    @if(!empty($options['sortable']))
                        <td><span class="icon-arrows-alt-v glass-btn-secondary text-xs px-2 py-1 handle cursor-grab"></span></td>
                    @endif

                    {!! $template !!}

                    <td>
                        <div class="remove glass-btn-danger text-xs cursor-pointer inline-flex items-center gap-1">
                            <i class="icon-trash"></i> {{ trans('admin.remove') }}
                        </div>
                    </td>
                </tr>
            </template>

            @if($options['allowCreate'])
                <div class="px-5 py-3">
                    <div class="add glass-btn-success text-sm cursor-pointer inline-flex items-center gap-1">
                        <i class="icon-plus"></i> {{ trans('admin.new') }}
                    </div>
                </div>
            @endif
        </div>
@include("admin::form._footer")
