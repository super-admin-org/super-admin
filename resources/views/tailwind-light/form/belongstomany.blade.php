@include("admin::form._header")

        <select class="glass-select {{$class}} hidden" name="{{$name}}[]" multiple="multiple" data-placeholder="{{ $placeholder }}" {!! $attributes !!} >
            @foreach($options as $select => $option)
                <option value="{{$select}}" {{  in_array($select, (array)old($column, $value)) ?'selected':'' }}>{{$option}}</option>
            @endforeach
        </select>

        <div class="belongstomany-{{ $class }} belongstomany belongsto-selected-rows">
            {!! $grid->render() !!}
            <template class="empty">
                @include('admin::grid.empty-grid')
            </template>
        </div>

@include("admin::form._footer")
