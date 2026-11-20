<select class="glass-select {{ $class }}" name="{{$name}}[]" multiple='multiple'>
    <option></option>
    @foreach($options as $select => $option)
        <option value="{{$select}}" {{ in_array((string)$select, (array)$value) ?'selected':'' }}>{{$option}}</option>
    @endforeach
</select>
