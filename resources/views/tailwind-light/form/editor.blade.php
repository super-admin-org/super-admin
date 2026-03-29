@include("admin::form._header")

        <textarea class="glass-input {{$class}}" id="{{$id}}" name="{{$name}}" placeholder="{{ $placeholder }}" {!! $attributes !!} >{{ old($column, $value) }}</textarea>

@include("admin::form._footer")
