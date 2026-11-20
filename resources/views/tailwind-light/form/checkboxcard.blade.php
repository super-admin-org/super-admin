@include("admin::form._header")

        <div class="flex flex-wrap gap-2">
        @foreach($options as $option => $label)
            <label class="cursor-pointer">
                <input type="checkbox" name="{{$name}}" value="{{$option}}" id="{{$name}}-{{$option}}" class="sr-only peer {{$class}}" {{ ($option == old($column, $value)) || ($value === null && in_array($label, $checked)) ?'checked':'' }} {!! $attributes !!} />
                <span class="inline-flex items-center px-3 py-1.5 rounded-lg border text-sm transition-all cursor-pointer peer-checked:bg-primary-500 peer-checked:text-white peer-checked:border-primary-500 bg-white/60 text-gray-700 border-gray-200/60 hover:border-primary-300">
                    {{$label}}
                </span>
            </label>
        @endforeach
        </div>

        <input type="hidden" name="{{$name}}[]">

@include("admin::form._footer")
