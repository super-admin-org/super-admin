@include("admin::form._header")

    <label class="relative inline-flex items-center cursor-pointer">
        <input type="hidden" name="{{$name}}" id="{{$id}}" value="{{ old($column, $value) }}" />
        <input class="sr-only peer {{$class}}" name="{{$name}}_cb" type="checkbox" id="{{$name}}_cb" {{ !empty(old($column, $value)) ? 'checked' : '' }} {!! $attributes !!} onchange="document.querySelector('#{{$id}}').value = (this.checked ? 'on' : 'off')" />
        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-500"></div>
    </label>

@include("admin::form._footer")
