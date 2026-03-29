@include("admin::form._header")

        @foreach($options as $option => $label)
            <label class="inline-flex items-center gap-2 {{ !$stacked ? 'mr-4 inline' : 'flex mb-2' }} cursor-pointer">
                <input class="border-gray-300 text-primary-500 focus:ring-primary-400 {{$class}}" type="radio" id="{{$name}}-{{$option}}" name="{{$name}}" value="{{$option}}" {{ ($option == old($column, $value)) || ($value === null && in_array($label, $checked)) ?'checked':'' }} {!! $attributes !!} />
                <span class="text-sm text-gray-700">{{$label}}</span>
            </label>
        @endforeach

@include("admin::form._footer")
