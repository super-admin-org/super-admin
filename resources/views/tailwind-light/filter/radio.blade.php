@foreach($options as $option => $label)
    <label class="inline-flex items-center gap-2 block mb-2 cursor-pointer">
        <input type="radio" class="border-gray-300 text-primary-500 focus:ring-primary-400" id="{{$id}}-{{$option}}" name="{{$name}}" value="{{$option}}" {{ ((string)$option === request($name, is_null($value) ? '' : $value)) ? 'checked' : '' }} />
        <span class="text-sm text-gray-700">{{$label}}</span>
    </label>
@endforeach
