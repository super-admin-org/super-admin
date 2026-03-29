@include("admin::form._header")

    <div class="flex">
        <button type="button" id="{{$id}}-button-min" class="glass-btn-secondary rounded-r-none px-3 minus"><i class="icon-minus"></i></button>
        <input {!! $attributes !!} class="rounded-none border-x-0" />
        <button type="button" id="{{$id}}-button-plus" class="glass-btn-secondary rounded-l-none px-3 plus"><i class="icon-plus"></i></button>
    </div>

@include("admin::form._footer")
