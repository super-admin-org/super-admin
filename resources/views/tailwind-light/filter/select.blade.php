<select class="glass-select {{ $class }}" name="{{$name}}">
    <option></option>
    @foreach($options as $select => $option)
        <option value="{{$select}}" {{ (string)$select === (string)request($name, $value) ?'selected':'' }}>{{$option}}</option>
    @endforeach
</select>
