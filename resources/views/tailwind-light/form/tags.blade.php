@include("admin::form._header")

    <input class="glass-input {{$class}}" name="{{$name}}[]" data-placeholder="{{ $placeholder }}" {!! $attributes !!} value="{{implode(",",$value)}}" />

@include("admin::form._footer")
