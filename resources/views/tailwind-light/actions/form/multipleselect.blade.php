<div class="mb-4">
    <label class="block text-sm font-medium text-gray-600 mb-1">{{ $label }}</label>
    <select class="glass-select {{$class}}" name="{{$name}}[]" multiple="multiple" {!! $attributes !!} >
        @foreach($options as $select => $option)
            <option value="{{$select}}" {{ in_array($select, (array)old($column, $value)) ?'selected':'' }}>{{$option}}</option>
        @endforeach
    </select>
    @include('admin::actions.form.help-block')
</div>
