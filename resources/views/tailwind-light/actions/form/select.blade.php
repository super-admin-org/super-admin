<div class="mb-4">
    <label class="block text-sm font-medium text-gray-600 mb-1">{{ $label }}</label>
    <select class="glass-select {{$class}}" name="{{$name}}" {!! $attributes !!} >
        <option value=""></option>
        @foreach($options as $select => $option)
            <option value="{{$select}}" {{ $select == old($column, $value) ?'selected':'' }}>{{$option}}</option>
        @endforeach
    </select>
    @include('admin::actions.form.help-block')
</div>
