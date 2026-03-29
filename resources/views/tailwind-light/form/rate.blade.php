@include("admin::form._header")

        <div class="flex w-36">
            <input type="text" id="{{$id}}" name="{{$name}}" value="{{ old($column, $value) }}" class="glass-input text-right {{$class}}" placeholder="0" {!! $attributes !!} />
            <span class="inline-flex items-center px-3 text-sm text-gray-500 bg-gray-50/50 border border-l-0 border-gray-200/60 rounded-r-lg">%</span>
        </div>

@include("admin::form._footer")
