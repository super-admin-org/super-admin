@foreach($options as $option => $label)
    <label class="inline-flex items-center gap-2 {{ $inline ? 'mr-4' : 'block mb-2' }} cursor-pointer">
        <input type="checkbox" class="rounded border-gray-300 text-primary-500 focus:ring-primary-400" id="{{$id}}-{{$option}}" name="{{$name}}[]" value="{{$option}}" {{ in_array((string)$option, request($name, is_null($value) ? [] : $value)) ? 'checked' : '' }} />
        <span class="text-sm text-gray-700">{{$label}}</span>
    </label>
@endforeach
