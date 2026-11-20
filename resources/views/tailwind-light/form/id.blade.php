@include("admin::form._header")

        <input type="text" id="{{$id}}" name="{{$name}}" value="{{$value}}" class="glass-input bg-gray-50/50" readonly {!! $attributes !!} />

@include("admin::form._footer")
