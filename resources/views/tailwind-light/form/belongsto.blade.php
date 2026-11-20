@include("admin::form._header")

    <select class="glass-select {{$class}} hidden" name="{{$name}}" {!! $attributes !!} >
        <option value=""></option>
        @foreach($options as $select => $option)
            <option value="{{$select}}" {{ $select == old($column, $value) ?'selected':'' }}>{{$option}}</option>
        @endforeach
    </select>

    <div class="belongsto-{{ $class }} belongsto belongsto-selected-rows">

        {!! $grid->render() !!}

        <template class="empty">
            @include('admin::grid.empty-grid')
        </template>
    </div>

@include("admin::form._footer")
