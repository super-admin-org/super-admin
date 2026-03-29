@include("admin::form._header")

    <input type="range" class="{{$class}} w-full accent-primary-500" name="{{$name}}" data-from="{{ old($column, $value) }}" {!! $attributes !!} />

@include("admin::form._footer")
